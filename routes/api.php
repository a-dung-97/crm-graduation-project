<?php


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

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('setup', 'AuthController@setup');
    Route::get('resend/{user}', 'AuthController@resendVerifyEmail');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::group(['middleware' => 'auth'], function () {
    Route::apiResource('departments', 'DepartmentController', ['except' => ['show']]);
    Route::apiResource('positions', 'PositionController', ['except' => ['show']]);
    Route::post('users/avatar', 'UserController@changeAvatar');
    Route::get('users/company', 'UserController@getCompany');
    Route::put('users/company', 'UserController@updateCompany');
    Route::apiResource('users', 'UserController', ['except' => ['show']]);
});
