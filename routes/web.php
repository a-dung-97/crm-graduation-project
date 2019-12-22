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

Route::get('/home', function () {
    return view('welcome');
});
Route::get('/verify-email/{email_token}', 'AuthController@verifyEmail')->name('verify-email');
Route::get('/confirm-email/{email_token}', 'EmailAddressController@verify')->name('confirm-email');

Route::get('invite/{invite_code}', 'UserController@comfirmInvitationEmail')->name('invite');
Route::post('invite/{invite_code}', 'UserController@acceptInvitation');

Route::get('test-send', 'EmailCampaignController@send');
