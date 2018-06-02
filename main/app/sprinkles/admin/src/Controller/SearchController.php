<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Admin\Controller;

use function GuzzleHttp\Psr7\str;
use UserFrosting\Fortress\RequestDataTransformer;
use UserFrosting\Fortress\RequestSchema;
use UserFrosting\Fortress\ServerSideValidator;
use UserFrosting\Sprinkle\Core\Controller\SimpleController;
use UserFrosting\Support\Exception\ForbiddenException;
use UserFrosting\Support\Exception\BadRequestException;
use UserFrosting\Support\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;
use Illuminate\Database\Capsule\Manager as DB;

/**
 * Controller class for user-related requests, including listing users, CRUD for users, etc.
 *
 * @author Alex Weissman (https://alexanderweissman.com)
 */
class SearchController extends SimpleController
{

    /**
     * Searches a user by name, username and email // TODO: Maybe not allowed to search everyone?
     *
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws NotFoundException
     */
    public function ByUsername(Request $request, Response $response, $args) {
        $classMapper = $this->ci->classMapper;
        $users = $classMapper->createInstance('user')
            ->where("first_name", "like", "%" . $args["search_term"] . "%")
            ->orWhere("last_name", "like", "%" . $args["search_term"] . "%")
            ->orWhere(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'LIKE', "%" . $args["search_term"] . "%")
            ->orWhere("user_name", "like", "%" . $args["search_term"] . "%")
            ->get();

        foreach ($users as $number => $user) {
            $users[$number]["avatar"] = $user->avatar;
        }

        if (count($users) === 0) throw new NotFoundException();
        return $response->withJson($users, 200, JSON_PRETTY_PRINT);
    }
}
