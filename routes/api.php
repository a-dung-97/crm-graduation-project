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

    Route::post('leads/convert/{lead}', 'LeadController@convert');

    Route::apiResource('leads', 'LeadController');

    Route::put('tasks/{task}/finish', 'TaskController@finishTask');
    Route::apiResource('tasks', 'TaskController');
    Route::apiResource('tasks', 'TaskController');
    Route::post('tasks/{type}/{id}', 'TaskController@addTask');
    Route::get('tasks/{type}/{id}', 'TaskController@getTasks');

    Route::get('customers/{customer}/{type}', 'CustomerController@getRelatedInfo');
    Route::apiResource('customers', 'CustomerController');
    Route::apiResource('contacts', 'ContactController');
    Route::apiResource('opportunities', 'OpportunityController');

    Route::get('orders/{order}/send', 'OrderController@sendOrder');
    Route::get('quotes/{quote}/send', 'QuoteController@sendQuote');
    Route::get('quotes/{quote}/order', 'QuoteController@getOrders');
    Route::get('orders/{order}/invoice', 'OrderController@getInvoices');
    Route::apiResource('quotes', 'QuoteController');
    Route::apiResource('orders', 'OrderController');


    Route::apiResource('cashbooks', 'CashbookController');


    Route::apiResource('invoices', 'InvoiceController');
    Route::put('bills/{bill}/verify', 'BillController@verify');

    Route::apiResource('bills', 'BillController', ['except' => 'show']);

    Route::put('email-addresses/{email_address}/primary', 'EmailAddressController@setPrimary');
    Route::get('email-addresses/{email_address}/confirm', 'EmailAddressController@sendConfirmEmail');
    Route::apiResource('email-addresses', 'EmailAddressController');
    Route::post('mailing-lists/members', 'MailingListController@addMembers');
    Route::delete('mailing-lists/{mailing_list}/members', 'MailingListController@deleteMembers');
    Route::apiResource('mailing-lists', 'MailingListController');
    Route::apiResource('lead-score-rules', 'LeadScoreRuleController');
    Route::apiResource('email-templates', 'EmailTemplateController');
    Route::apiResource('webforms', 'WebformController');
    Route::apiResource('emails', 'EmailController', ['only' => ['index', 'store']]);
    Route::get('email-campaigns/{email_campaign}/list', 'EmailCampaignController@getListEmail');
    Route::apiResource('email-campaigns', 'EmailCampaignController', ['except' => 'update']);
});
Route::post('tracking', 'MailController@tracking');
Route::post('test', 'TestController@test');
Route::get('test/{lead}', 'LeadScoreRuleController@test');


Route::get('webforms/{webform}/iframe', 'WebformController@getWebformFromIframe');
Route::post('webforms/{webform}/lead', 'WebformController@createLead');

//Webhooks


Route::group(['prefix' => 'webhooks'], function () {
    Route::group(['prefix' => 'mailgun'], function () {
        Route::post('tracking', 'MailgunWebhookController@handle');
    });
});
