<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', 'Api\Auth\RegisterController@register');
Route::post('login', 'Api\Auth\LoginController@login');
Route::post('refresh', 'Api\Auth\LoginController@refresh');

Route::middleware('auth:api')->group(function () {

	Route::post('logout', 'Api\Auth\LoginController@logout');

	Route::get('users', 'Api\User\UserController@getAllUsers');
	Route::get('users/me', 'Api\User\UserController@getCurrentUser');
	Route::get('users/{user}', 'Api\User\UserController@getUser');
	Route::put('users/me', 'Api\User\UserController@updateCurrentUser');
	Route::put('users/{user}', 'Api\User\UserController@updateUser');
	Route::patch('users/me/profileimage', 'Api\User\UserController@updateCurrentUserProfileImage');
	Route::patch('users/{user}/profileimage', 'Api\User\UserController@updateUserProfileImage');
	Route::post('users/{user}/like', 'Api\User\UserController@likeUser');

	//return $request->user();
});
