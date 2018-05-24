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
use Slim\Http\UploadedFile;
use Illuminate\Database\Capsule\Manager as DB;

/**
 * Controller class for user-related requests, including listing users, CRUD for users, etc.
 *
 * @author Alex Weissman (https://alexanderweissman.com)
 */
class PostController extends SimpleController
{

    /**
     * Gets the feed of the requested user (for non-administrators only own feed allowed)
     *
     * @param Request $request
     * @param Response $response
     * @param $args
     * @throws BadRequestException
     * @throws NotFoundException
     */
    public function getFeed(Request $request, Response $response, $args) {
        $user = $this->getUserFromParams($args);

        // If the user doesn't exist, return 404
        if (!$user) {
            throw new NotFoundException();
        }

        // Get friends first
        $UsersFriends = DB::select("SELECT id FROM (SELECT user_id AS id FROM user_follow WHERE followed_by_id = $user->id UNION ALL SELECT followed_by_id FROM user_follow WHERE user_id = $user->id) t GROUP BY id HAVING COUNT(id) > 1");

        /** @var UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;
        $ImagesFromFriends = "";
        $config = $this->ci->config;
        foreach ($UsersFriends as $Key => $UsersFriendId) { // NOT THAT EFFICIENT... (get images from all friends in an array)
            $UsersFriendInformation = $classMapper->createInstance('user')// raw select doesnt work with instance
                ->where('id', $UsersFriendId->id)
                ->get();

            $ImagesFromFriends = DB::table('image_posts')
                ->where('UserID', '=', $UsersFriendInformation[0]->id)
                ->select('PostID as image_id')
                ->get();

            foreach ($ImagesFromFriends as $ImageFromFriend) {
                $ImageFromFriend->image_url = $config["site.uri.public"] . "/image/" . $ImageFromFriend->image_id;
                $ImageFromFriend->username =  $UsersFriendInformation[0]->user_name; // ADD USERNAME TO IMAGE ID
                $ImageFromFriend->avatar =  $UsersFriendInformation[0]->avatar;
            }
        }

        return $response->withJson($ImagesFromFriends, 200, JSON_PRETTY_PRINT);
    }

    /**
     * Shows the requested image
     *
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function showImage(Request $request, Response $response, $args) {
        // check if user is authorized
        $authorizer = $this->ci->authorizer;
        $currentUser = $this->ci->currentUser;
        if (!$authorizer->checkAccess($currentUser, 'view_image')) {
            throw new ForbiddenException();
        }
        $postID = $args['post_id'];

        // get filename from database
        $FileRequestedImage = DB::table('image_posts')
            ->where('PostID', '=', $postID)
            ->value('File');

        if ($FileRequestedImage) {
            $FileType = pathinfo($FileRequestedImage, PATHINFO_EXTENSION);

            // echo image
            $response->write(file_get_contents(__DIR__ . '/../../../../../uploads/' . $FileRequestedImage));
            return $response->withHeader('Content-type', 'image/' . $FileType);
        } else {
            throw new NotFoundException();
        }
    }

    /**
     * posts a image
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws ForbiddenException
     */
    public function postImage(Request $request, Response $response) {
        // check if user is authorized
        $authorizer = $this->ci->authorizer;
        $currentUser = $this->ci->currentUser;
        if (!$authorizer->checkAccess($currentUser, 'post_image')) {
            throw new ForbiddenException();
        }

        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles['image'];

        if (!strpos($uploadedFile->getClientMediaType(), "mage")) {
            return $response->withStatus(415);
        } else if ($uploadedFile->getError() === 1) {
            return $response->withStatus(406);
        } else if ($uploadedFile->getSize() > 10485760) {
            return $response->withStatus(413);
        } else { // Upload is accepted
            // Move file to upload directory
            $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
            $basename = bin2hex(random_bytes(8));
            $filename = sprintf('%s.%0.8s', $basename, $extension);
            $uploadedFile->moveTo(__DIR__ . '/../../../../../uploads' . DIRECTORY_SEPARATOR . $filename);

            // Store in Database
            DB::table('image_posts')
                ->insert(['UserID' => $currentUser->id, 'File' => $filename]);

            return $response->write('Uploaded successfully! <br/>');
        }
    }

    /**
     * @param $params
     * @return mixed
     * @throws BadRequestException
     */
    protected function getUserFromParams($params) {
        // Load the request schema
        $schema = new RequestSchema('schema://requests/user/get-by-username.yaml');

        // Whitelist and set parameter defaults
        $transformer = new RequestDataTransformer($schema);
        $data = $transformer->transform($params);

        // Validate, and throw exception on validation errors.
        $validator = new ServerSideValidator($schema, $this->ci->translator);
        if (!$validator->validate($data)) {
            $e = new BadRequestException();
            foreach ($validator->errors() as $idx => $field) {
                foreach ($field as $eidx => $error) {
                    $e->addUserMessage($error);
                }
            }
            throw $e;
        }

        /** @var UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
        $classMapper = $this->ci->classMapper;

        // Get the user to delete
        $user = $classMapper->staticMethod('user', 'where', 'user_name', $data['user_name'])
            ->first();

        return $user;
    }
}
