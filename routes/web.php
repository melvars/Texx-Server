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
        Route::get('/', ['as' => 'MessageChat', 'uses' => 'SocketController@writeMessage']);
        Route::get('avatar/{user_id}', ['as' => 'GetAvatar', 'uses' => 'ImageController@getAvatar']);
        Route::get('profile', ['as' => 'GetProfileSettings', 'uses' => 'UserController@Profile']);
        Route::get('admin', ['as' => 'GetAdminDashboard', 'uses' => 'AdminController@Dashboard']);

        Route::post('avatar', ['as' => 'UpdateAvatar', 'uses' => 'UserController@updateAvatar']);
        Route::post('sendMessage', ['as' => 'SendMessage', 'uses' => 'SocketController@sendMessage']);
    });
