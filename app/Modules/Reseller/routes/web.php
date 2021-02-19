<?php

//Route::get('reseller', 'ResellerController@index');
Route::get('/', 'LoginController@loginForm')->name('reseller.loginForm');
Route::post('login', 'LoginController@authenticate')->name("reseller.login");
Route::post('logout', 'LoginController@logout')->name("reseller.logout");


//Route::get('reseller/register', 'RegisterController@showRegisterForm')->name("reseller.registerForm");
//Route::post('reseller/register', 'RegisterController@createreseller')->name("reseller.register");

Route::middleware('reseller_auth')->group(function () {
    
	Route::get('dashboard', 'ResellerController@dashboard')->name('reseller.dashboard');
	Route::get('profile', 'ResellerController@profile')->name('reseller.profile');
	Route::get('edit-profile/{id}', 'ResellerController@editProfile')->name('reseller.editProfile');
	Route::post('update-profile/{id}', 'ResellerController@updateProfile')->name('reseller.updateProfile');
	Route::get('change-password', 'ResellerController@changePassword')->name('reseller.changePassword');
	Route::post('update-password', 'ResellerController@updatePassword')->name('reseller.updatePassword');

	Route::get('subscribers', 'SubscriberController@subscribersList')->name('reseller.subscribersList');
	Route::get('add-subscriber', 'SubscriberController@AddNew')->name('reseller.subscriberAdd');
	Route::post('store-subscriber', 'SubscriberController@StoreNewSubscriber')->name('reseller.StoreNewSubscriber');
	Route::get('subscriber/{id}', 'SubscriberController@subscriberDetails')->name('reseller.subscriberView');
	Route::post('group-autoresponders', 'SubscriberController@groupResponders')->name('reseller.groupResponders');

	Route::get('payment-modes', 'PaymentModeController@paymentModesList')->name('reseller.paymentModesList');
	Route::get('add-payment-mode', 'PaymentModeController@AddNew')->name('reseller.AddNewPaymetMode');
	Route::post('store-payment-mode', 'PaymentModeController@StoreNewPaymentMode')->name('reseller.StoreNewPaymentMode'); 
	Route::get('delete-payment-mode/{id}', 'PaymentModeController@DeletePaymentMode')->name('reseller.DeletePaymentMode'); 
	Route::get('edit-payment-mode/{id}', 'PaymentModeController@EditPaymentMode')->name('reseller.EditPaymentMode'); 
	Route::post('update-payment-mode', 'PaymentModeController@UpdatePaymentMode')->name('reseller.UpdatePaymentMode');
	Route::get('manage-plans/{gateway_id?}', 'PlansController@plansList')->name('reseller.plansList');   
	Route::get('add-plan', 'PlansController@AddNewPlan')->name('reseller.AddNewPlan');    
	Route::post('store-plan', 'PlansController@StoreNewPlan')->name('reseller.StoreNewPlan');
	Route::get('delete-plan/{id}', 'PlansController@DeletePlan')->name('reseller.DeletePlan'); 
	Route::get('edit-plan/{id}', 'PlansController@EditPlan')->name('reseller.EditPlan'); 
	Route::post('update-plan', 'PlansController@UpdatePlan')->name('reseller.UpdatePlan');
	
	Route::get('send-license/{id}','SubscriberController@sendLicense')->name('reseller.sendLicense');

});