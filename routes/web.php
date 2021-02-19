<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

 Route::get('/',function() {
	 
	 abort(404);
 //  return redirect()->route('admin.loginForm');
 });
//Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

Route::get('/getSubscriptionId', 'FrontendController@getSubscriptionId')->name('getSubscriptionId');



Route::get('/plans/{hash?}', 'FrontendController@showPlans')->name('showPlans');

// Route::get('authenticate','FrontendController@auth');

// Route::get('plan-details','FrontendController@planData');

// Route::get('/insert_user', function(){
	// $user = new App\User();
	// $user->password = Hash::make('shiv');
	// $user->email = 'shiv@gmail.com';
	// $user->name = 'Shiv';
	// $user->save();
	// echo "Record inserted successfully.";
// });


