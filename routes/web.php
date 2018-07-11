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

// Route should not use authentication!
Route::get('verify/email/{token}', 'Auth\RegisterController@verifyEmail');

Auth::routes();
Route::middleware('auth', 'throttle:30') // throttle to 30 per minute
    ->group(function () {
        Route::get('/', ['as' => 'writeMessage', 'uses' => 'SocketController@writeMessage']);
        Route::get('avatar/{user_id}', 'ImageController@getAvatar');
        Route::get('profile', 'UserController@Profile');

        Route::post('avatar', 'UserController@updateAvatar');
        Route::post('sendMessage', 'SocketController@sendMessage');
    });
