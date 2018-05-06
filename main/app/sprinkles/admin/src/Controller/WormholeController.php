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
        $currentUser = $this->ci->currentUser; // FOR DATABASE QUERY

        $access_token = $args['access_token'];
        if (DB::table('public_keys')
            ->where('UserID', 1)
            ->where('Key', '=', $access_token)
            ->exists()) {
            $user_id = $args['user_id'];
            $session_id = $args['session_id'];
            $session_file = file_get_contents("../app/sessions/" . $session_id);
            $session_user_id = unserialize(substr($session_file, strpos($session_file, "account|") + 8))["current_user_id"];
            if ($session_user_id === $user_id) {
                return $response->withStatus(200);
            } else {
                throw new NotFoundException();
            }
        } else {
            throw new NotFoundException(); // IT'S A FORBIDDEN EXCEPTION BUT IT'S SECRET! PSSSHT
        }
    }

    public function getUsername(Request $request, Response $response, $args) {
        $currentUser = $this->ci->currentUser; // FOR DATABASE QUERY

        $access_token = $args['access_token'];
        if (DB::table('public_keys')
            ->where('UserID', 1)
            ->where('Key', '=', $access_token)
            ->exists()) {
            $user_id = $args['user_id'];
            $username =(DB::table('users')
                ->where('id', $user_id)
                ->value('user_name'));
            $response->write($username);
        } else {
            throw new NotFoundException(); // IT'S A FORBIDDEN EXCEPTION BUT IT'S SECRET! PSSSHT
        }
    }
}