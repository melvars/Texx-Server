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

    public function newMessage(Request $request, Response $response, $args) {
        if ($this->verifyAccessToken($args)) {
            $sender_id = $args['sender_id'];
            $receiver_id = $args['receiver_id'];
            $message = $args['message'];
            if (($sender_id != $receiver_id) && $message) {
                DB::table('chat_messages')
                    ->insert(['sender_id' => $sender_id, 'receiver_id' => $receiver_id, 'message' => $message]);
                return $response->withStatus(200);
            } else {
                throw new BadRequestException();
            }
        }
    }

    public function getInfo(Request $request, Response $response, $args) {
        if ($this->verifyAccessToken($args)) {
            $user = DB::table('users')
                ->where('id', $args["user_id"])
                ->first();
            if (!$user) {
                throw new NotFoundException($request, $response);
            }
            $classMapper = $this->ci->classMapper;
            $user = $classMapper->createInstance('user')
                ->where('user_name', $user->user_name)
                ->joinLastActivity()
                ->with('lastActivity', 'group')
                ->first();

            $result = $user->toArray();
            $result["avatar"] = $user->avatar;
            return $response->withJson($result, 200, JSON_PRETTY_PRINT);
        }
    }

    private function verifyAccessToken($args) {
        $currentUser = $this->ci->currentUser; // FOR DATABASE QUERY
        $access_token = $args['access_token'];
        if (DB::table('access_token')
            ->where('id', 1)
            ->where('token', '=', $access_token)
            ->exists()) {
            return true;
        } else {
            throw new NotFoundException();
        }
    }
}