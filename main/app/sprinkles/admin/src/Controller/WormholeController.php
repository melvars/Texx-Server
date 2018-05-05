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
use UserFrosting\Session\Session;

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
            $session = new Session();
            $session->start();
            $response->write($session->all()["account"]["current_user_id"]);
        } else {
            throw new ForbiddenException();
        }
    }
}