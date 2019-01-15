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
Route::match(['get', 'post'],'/webCounterQuery',function(){
	return view('pages.webCounterQuery');
});
Route::get('email-verification/error', 'Auth\RegisterController@getVerificationError')->name('email-verification.error');
Route::get('email-verification/check/{token}', 'Auth\RegisterController@getVerification')->name('email-verification.check');
Route::get('/registeredByConfirm','Auth\RegisterController@showRegistered');

Route::get('/', "PublicController@index");
Route::get('/manage', "HomeController@manage")->name('manage');
Route::get('/home', "HomeController@index")->name('home');

Route::resource("/device","DeviceController");
Route::resource("/advice","AdviceController");
Route::resource("/devicelog","DevicelogController");
Route::resource("/userdetail","UserdetailController");
Route::resource("/webcounter","WebCounterController");
Route::resource("/user","UserController");
Route::resource("/memberrec","MemberRecController");
Auth::routes();

/*
Route::get('/home', function(){
    return redirect("/");
});
*/
//Route::get('/home', 'PublicController@index')->name('home');
