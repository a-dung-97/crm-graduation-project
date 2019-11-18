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

use Illuminate\Http\Request;

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

    Route::post('products/{product}/note', 'ProductController@addNoteToProduct');
    Route::post('products/{product}/file', 'ProductController@addFileToProduct');
    Route::get('products/{product}/note', 'ProductController@getNotes');
    Route::get('products/{product}/file', 'ProductController@getFiles');

    Route::apiResource('products', 'ProductController');

    Route::post('notes/{type}/{id}', 'NoteController@addNote');
    Route::get('notes/{type}/{id}', 'NoteController@getNotes');
    Route::apiResource('notes', 'NoteController', ['except' => ['show', 'store']]);

    Route::post('files/download', 'FileController@download');
    Route::post('files/{type}/{id}', 'FileController@addFiles');
    Route::get('files/{type}/{id}', 'FileController@getFiles');
    Route::apiResource('files', 'FileController', ['only' => ['destroy']]);

    Route::get('tags', 'TagController@index');
    Route::put('tags/{type}/{id}', 'TagController@changeTags');
    Route::get('tags/{type}/{id}', 'TagController@getTags');
    Route::delete('tags/{type}/{id}', 'TagController@deleteTag');



    Route::apiResource('receipts', 'ReceiptController');
    Route::apiResource('issues', 'IssueController');
    Route::apiResource('warehouses', 'WarehouseController');
    Route::get('inventories', 'InventoryController@index');

    Route::get('catalogs/list', 'CatalogController@listCatalogs');
    Route::apiResource('catalogs', 'CatalogController', ['except' => 'show']);

    Route::apiResource('leads', 'LeadController');

    Route::apiResource('tasks', 'TaskController');
    Route::post('tasks/{type}/{id}', 'TaskController@addTask');
    Route::get('tasks/{type}/{id}', 'TaskController@getTasks');
});
Route::post('tracking', 'MailController@tracking');

Route::get('test', function (Request $request) {
    return $request->query('date');
});
