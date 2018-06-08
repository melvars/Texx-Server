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
$app->get('/image/{post_id}', 'UserFrosting\Sprinkle\Admin\Controller\PostController:showImage')->add('authGuard');

$app->get('/api/feed/{user_name}', 'UserFrosting\Sprinkle\Admin\Controller\PostController:getFeed')->add('authGuard');

$app->group('/api/posts', function () {
    $this->post('/image', 'UserFrosting\Sprinkle\Admin\Controller\PostController:postImage');
})->add('authGuard');