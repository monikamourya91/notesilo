<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::post("login",'ExtensionAPIs\APIController@login');
Route::post("signup",'ExtensionAPIs\APIController@signup');
Route::post("logout",'ExtensionAPIs\APIController@logout');
Route::post("forgot-password",'ExtensionAPIs\APIController@forgotPassword');
Route::post("change-password",'ExtensionAPIs\APIController@changePassword');
Route::get("get-all-autoresponders",'ExtensionAPIs\APIController@getAllAutoresponders');
Route::post("update-autoresponder-credentials",'ExtensionAPIs\APIController@updateAutoresponderCredentials');
Route::post("update-autoresponder-status",'ExtensionAPIs\APIController@updateAutoresponderStatus');
// Route::post("get-autoresponder-fields",'ExtensionAPIs\APIController@getAutoresponderFields');
Route::get("get-profile-data",'ExtensionAPIs\APIController@getProfileData');
Route::post("update-profile-data",'ExtensionAPIs\APIController@updateProfileData');
Route::get("get-active-autoresponders-count",'ExtensionAPIs\APIController@getActiveAutorespondersCount');
Route::post("add-leads-to-autoresponder",'ExtensionAPIs\APIController@addLeadsToAutoresponder');
Route::get("get-account-data",'ExtensionAPIs\APIController@getAccountData');
Route::post("get-user-data-for-site/{hash}",'ExtensionAPIs\APIController@getUserDataForSite');
Route::post("upgrade-user-plan",'ExtensionAPIs\APIController@upgradeUserPlan');

Route::post("export-leads",'ExtensionAPIs\APIController@exportLeads');
Route::post("paddle-notify",'ExtensionAPIs\PaddleNotificationController@paddleNotification');





