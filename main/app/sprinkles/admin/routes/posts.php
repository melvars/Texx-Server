<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/licenses/UserFrosting.md (MIT License)
 */

/**
 * Routes for posting.
 */

$app->get('/image/{PostID}', 'UserFrosting\Sprinkle\Admin\Controller\PostController:postImage')->add('authGuard');

$app->group('/api/posts', function () {
    $this->post('/image', 'UserFrosting\Sprinkle\Admin\Controller\PostController:postImage');
})->add('authGuard');