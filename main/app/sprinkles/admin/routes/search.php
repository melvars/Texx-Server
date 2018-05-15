<?php

/**
 * Routes for searching any kind of data we have
 */
$app->group('/search', function () {
    $this->get('/user/{user_name}', 'UserFrosting\Sprinkle\Admin\Controller\UserController:pageInfo');
})->add('authGuard');
