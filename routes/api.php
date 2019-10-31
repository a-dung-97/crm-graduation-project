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
    Route::get('departments/recursive', 'DepartmentController@getChildrenRecursive');
    Route::apiResource('departments', 'DepartmentController', ['except' => ['show']]);
    Route::get('positions/recursive', 'PositionController@getChildrenRecursive');
    Route::apiResource('positions', 'PositionController', ['except' => ['show']]);


    Route::post('users/avatar', 'UserController@changeAvatar');
    Route::get('users/company', 'UserController@getCompany');
    Route::put('users/company', 'UserController@updateCompany');
    Route::post('users/invite', 'UserController@inviteUser');
    Route::apiResource('users', 'UserController', ['except' => ['show']]);

    Route::apiResource('roles', 'RoleController', ['except' => ['show']]);

    Route::post('groups/user/{group}', 'GroupController@updateUsers');
    Route::apiResource('groups', 'GroupController', ['except' => ['show']]);

    Route::post('products/notes/{product}', 'ProductController@addNoteToProduct');
    Route::post('products/files/{product}', 'ProductController@addFileToProduct');
    Route::apiResource('products', 'ProductController');

    Route::apiResource('notes', 'NoteController');

    Route::apiResource('files', 'FileController', ['only' => ['destroy']]);
    Route::apiResource('receipts', 'ReceiptController');

    Route::apiResource('warehouses', 'WareHouseController');
});
