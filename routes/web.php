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
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::middleware('auth', 'throttle:30,1')->group(function () {
    Route::get('profile', 'UserController@Profile');
    Route::get('avatar/{user_id}', 'ImageController@getAvatar');
    Route::get('/', ['as' => 'writeMessage', 'uses' => 'SocketController@writeMessage']);

    Route::post('avatar', 'UserController@updateAvatar');
    Route::post('sendMessage', 'SocketController@sendMessage');
});
