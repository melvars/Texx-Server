<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Admin\Controller;

use UserFrosting\Fortress\RequestDataTransformer;
use UserFrosting\Fortress\RequestSchema;
use UserFrosting\Fortress\ServerSideValidator;
use UserFrosting\Sprinkle\Core\Controller\SimpleController;
use UserFrosting\Support\Exception\ForbiddenException;
use UserFrosting\Support\Exception\BadRequestException;
use UserFrosting\Support\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
use Illuminate\Database\Capsule\Manager as DB;
use UserFrosting\Sprinkle\Account\Authenticate\Authenticator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Session\FileSessionHandler;

/**
 * Controller class for user-related requests, including listing users, CRUD for users, etc.
 *
 * @author Alex Weissman (https://alexanderweissman.com)
 */
class WormholeController extends SimpleController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws NotFoundException
     */
    public function verify(Request $request, Response $response, $args) {
        if ($this->verifyAccessToken($args)) {
            $user_id = $args['user_id'];
            $session_id = $args['session_id'];
            $session_file = file_get_contents("../app/sessions/" . $session_id);
            $session_user_id = unserialize(substr($session_file, strpos($session_file, "account|") + 8))["current_user_id"];
            if ($session_user_id == $user_id) {
                return $response->withStatus(200);
            } else {
                throw new NotFoundException();
            }
        }
    }

    /**
     * @param $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws BadRequestException
     * @throws NotFoundException
     */
    public function newMessage($request, Response $response, $args) {
        if ($this->verifyAccessToken($args)) {
            $sender_id = $args['sender_id'];
            $receiver_id = $args['receiver_id'];
            $message = $request->getParsedBody()["message"];
            if (($sender_id != $receiver_id) && $message) {
                $MessageId = DB::table('chat_messages')
                    ->insertGetId(['sender_id' => $sender_id, 'receiver_id' => $receiver_id, 'message' => $message], 'message_id');
                $response->write($MessageId);
                return $response->withStatus(200);
            } else {
                throw new BadRequestException();
            }
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws NotFoundException
     */
    public function getInfo(Request $request, Response $response, $args) {
        /** @var UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        if ($this->verifyAccessToken($args)) {
            $user = $classMapper->staticMethod('user', 'where', 'id', $args['user_id'])
                ->first();
            if (!$user) {
                throw new NotFoundException($request, $response);
            }

            $UsersFollower = DB::table('user_follow')
                ->where('user_id', $user->id)
                ->join("users", "users.id", "=", "user_follow.followed_by_id")
                ->select("user_follow.followed_by_id as id", "users.user_name as username")
                ->get();

            $UsersFollows = DB::table('user_follow')
                ->where('followed_by_id', $user->id)
                ->join("users", "users.id", "=", "user_follow.user_id")
                ->select("user_follow.user_id as id", "users.user_name as username")
                ->get();

            $UsersFriends = DB::select("SELECT id FROM (SELECT user_id AS id FROM user_follow WHERE followed_by_id = $user->id UNION ALL SELECT followed_by_id FROM user_follow WHERE user_id = $user->id) t GROUP BY id HAVING COUNT(id) > 1");
            /** @var UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
            $classMapper = $this->ci->classMapper;
            foreach ($UsersFriends as $Key => $UsersFriendId) { // NOT THAT EFFICIENT...
                $UsersFriendInformation = $classMapper->createInstance('user')// select doesnt work with instance
                    ->where('id', $UsersFriendId->id)
                    ->get();
                $UsersFriends[$Key]->id = $UsersFriendInformation[0]->id;
                $UsersFriends[$Key]->username = $UsersFriendInformation[0]->user_name;
                $UsersFriends[$Key]->avatar = $UsersFriendInformation[0]->avatar;
                $UsersFriends[$Key]->full_name = $UsersFriendInformation[0]->full_name;
            }

            $result = $user->toArray();
            $result["avatar"] = $user->avatar;
            $result["followers"] = $UsersFollower;
            $result["follows"] = $UsersFollows;
            $result["friends"] = $UsersFriends;
            return $response->withJson($result, 200, JSON_PRETTY_PRINT);
        }
    }

    /**
     * @param $args
     * @return bool
     * @throws NotFoundException
     */
    private function verifyAccessToken($args) {
        $currentUser = $this->ci->currentUser; // FOR DATABASE QUERY
        $access_token = $args['access_token'];
        if (DB::table('access_token')
            ->where('id', 1)
            ->where('token', '=', $access_token)
            ->exists()) {
            return TRUE;
        } else {
            throw new NotFoundException(); // IT'S A FORBIDDEN
        }
    }
}