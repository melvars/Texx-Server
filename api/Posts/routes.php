<?php

$router->get('/posts', 'PostController@getAll');
$router->get('/posts/{id}', 'PostController@getById');
$router->post('/posts', 'PostController@create');
$router->put('/posts/{id}', 'PostController@update');
$router->delete('/posts/{id}', 'PostController@delete');
