<?php
/*
|--------------------------------------------------------------------------
| Routes for public keys
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
        Route::get('keys/cookie/public/', 'PublicKeyController@setUsersKeyByCookie');
        Route::get('keys/public/{user_id}', 'PublicKeyController@getUsersKey');
        Route::post('keys/public', 'PublicKeyController@setUsersKey');
    });
