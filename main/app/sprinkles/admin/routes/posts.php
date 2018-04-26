<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

/**
 * Routes for posting.
 */

$app->group('/api/posts', function () {
    $this->post('/image', 'UserFrosting\Sprinkle\Admin\Controller\PostController:postImage');
})->add('authGuard');
