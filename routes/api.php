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
    Route::get('password/reset', 'AuthController@sendEmailResetPassword');
    Route::post('password/reset', 'AuthController@resetPassword');
    Route::post('password/change', 'AuthController@changePassword');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('departments/recursive', 'DepartmentController@getChildrenRecursive');
    Route::apiResource('departments', 'DepartmentController', ['except' => ['show']]);
    Route::get('positions/recursive', 'PositionController@getChildrenRecursive');
    Route::apiResource('positions', 'PositionController', ['except' => ['show']]);


    Route::post('users/avatar', 'UserController@changeAvatar');
    Route::get('notifications', 'UserController@getNotifications');
    Route::put('notifications', 'UserController@markAsRead');
    Route::get('users/company', 'UserController@getCompany');
    Route::put('users/company', 'UserController@updateCompany');
    Route::post('users/invite', 'UserController@inviteUser');
    Route::apiResource('users', 'UserController', ['except' => ['show']]);

    Route::put('roles/{role}/menu', 'RoleController@updateMenu');
    Route::get('roles/{role}/menu', 'RoleController@getMenus');
    Route::apiResource('roles', 'RoleController', ['except' => ['show']]);
    Route::apiResource('menus', 'MenuController', ['only' => ['index']]);

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
    Route::put('tags/{type}', 'TagController@changeTags');
    Route::get('tags/{type}/{id}', 'TagController@getTags');
    Route::delete('tags/{type}', 'TagController@deleteTag');



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
    Route::post('tasks/{type}/{id}', 'TaskController@addTask');
    Route::get('tasks/{type}/{id}', 'TaskController@getTasks');

    Route::get('calls/{type}/{id}', 'CallController@getCalls');
    Route::apiResource('calls', 'CallController');
    Route::get('appointments/{type}/{id}', 'AppointmentController@getAppointments');
    Route::apiResource('appointments', 'AppointmentController');


    Route::get('customers/{customer}/{type}', 'CustomerController@getRelatedInfo');
    Route::apiResource('customers', 'CustomerController');
    Route::apiResource('contacts', 'ContactController');
    Route::get('opportunities/{opportunity}/orders', 'OpportunityController@getOrders');
    Route::get('opportunities/{opportunity}/quotes', 'OpportunityController@getQuotes');
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

    Route::put('email-automations/{email_automation}/active', 'EmailAutomationController@changeActive');
    Route::get('email-automations/{email_automation}/email', 'EmailAutomationController@getEmails');
    Route::post('email-automations/{email_automation}/email', 'EmailAutomationController@addEmail');
    Route::put('email-automations/email/{email}', 'EmailAutomationController@updateEmail');
    Route::apiResource('email-automations', 'EmailAutomationController');

    Route::post('mailing-lists/members', 'MailingListController@addMembers');
    Route::delete('mailing-lists/{mailing_list}/members', 'MailingListController@deleteMembers');
    Route::apiResource('mailing-lists', 'MailingListController');
    Route::apiResource('lead-score-rules', 'LeadScoreRuleController');
    Route::apiResource('email-templates', 'EmailTemplateController');
    Route::apiResource('webforms', 'WebformController');
    Route::get('reports/leads', 'ReportController@getLeads');
    Route::get('reports/email-campaigns', 'ReportController@getEmailCampaigns');
    Route::get('reports/revenue', 'ReportController@getRevenue');
    Route::get('reports/converted', 'ReportController@getConverted');
    Route::get('reports/tasks', 'ReportController@getTasks');
    Route::get('reports/products', 'ReportController@getProducts');
    Route::get('reports/debt', 'ReportController@getDebt');
    Route::apiResource('reports', 'ReportController');
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
