<?php

Route::get('admin', 'AdminController@index');
Route::get('admin/login', 'LoginController@loginForm')->name('admin.loginForm');
Route::post('admin/login', 'LoginController@authenticate')->name("admin.login");
Route::post('admin/logout', 'LoginController@logout')->name("admin.logout");


Route::get('admin/register', 'RegisterController@showRegisterForm')->name("admin.registerForm");
Route::post('admin/register', 'RegisterController@createAdmin')->name("admin.register");

Route::middleware('admin_auth')->group(function () {
    
	Route::get('admin/dashboard', 'AdminController@dashboard')->name('admin.dashboard')->middleware('admin_auth');
	Route::get('admin/profile', 'AdminController@profile')->name('admin.profile');
	Route::get('admin/edit-profile/{id}', 'AdminController@editProfile')->name('admin.editProfile');
	Route::post('admin/update-profile/{id}', 'AdminController@updateProfile')->name('admin.updateProfile');
	Route::get('admin/change-password', 'AdminController@changePassword')->name('admin.changePassword');
	Route::post('admin/update-password', 'AdminController@updatePassword')->name('admin.updatePassword');
	
	Route::get('admin/subscribers', 'SubscriberController@subscribersList')->name('admin.subscribersList');
	Route::get('admin/add-subscriber', 'SubscriberController@AddNew')->name('admin.subscriberAdd');
	Route::post('admin/store-subscriber', 'SubscriberController@StoreNewSubscriber')->name('admin.StoreNewSubscriber');
	Route::get('admin/subscriber/{id}', 'SubscriberController@subscriberDetails')->name('admin.subscriberView');
	Route::post('admin/update-subscriber', 'SubscriberController@updateSubscriber')->name('admin.updateSubscriber');
	/*Route::get('admin/import-subscribers', 'SubscriberController@importSubscribers')->name('admin.importSubscribers');*/
	Route::post('admin/group-autoresponders', 'SubscriberController@groupResponders')->name('admin.groupResponders');

	Route::get('admin/resellers', 'ResellerController@resellersList')->name('admin.resellersList');
	Route::get('admin/reseller/{id}', 'ResellerController@resellerDetails')->name('admin.resellerView');
	Route::post('admin/update-reseller', 'ResellerController@updateReseller')->name('admin.updateReseller');
	Route::get('admin/send-license/{id}','SubscriberController@sendLicense')->name('admin.sendLicense');
    
    Route::get('admin/subscriber/change-plan/{id}','SubscriberController@subscriberChangePlan')->name('admin.subscriberChangePlan');
	Route::post('admin/subscriber/update-plan','SubscriberController@updateSubscription')->name('admin.updateSubscription');

});
