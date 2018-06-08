<?php

/**
 * Routes for searching any kind of data we have
 */
$app->group('/api/search', function () {
    $this->get('/user/{search_term}', 'UserFrosting\Sprinkle\Admin\Controller\SearchController:ByUsername');
})->add('authGuard');
