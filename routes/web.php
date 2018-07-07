<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Auth::routes();
Route::middleware('auth', 'throttle:30') // throttle to 30 per minute
    ->group(function () {
        Route::get('/', ['as' => 'writeMessage', 'uses' => 'SocketController@writeMessage']);
        Route::get('avatar/{user_id}', 'ImageController@getAvatar');
        Route::get('profile', 'UserController@Profile');
        Route::get('keys/cookie/public/', 'PublicKeyController@setUsersKeyCookie'); // actually it's a post but it has to be get (via cookie) => TODO:
        Route::get('keys/public/{user_id}', 'PublicKeyController@getUsersKey');

        Route::post('avatar', 'UserController@updateAvatar');
        Route::post('keys/public', 'PublicKeyController@setUsersKey');
        Route::post('sendMessage', 'SocketController@sendMessage');
    });
