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

Route::get('/', "PublicController@index");
Route::get('/manage', "HomeController@index")->name('home');


Route::resource("/device","DeviceController");
Route::resource("/advice","AdviceController");
Route::resource("/devicelog","DevicelogController");
Route::resource("/userdetail","UserdetailController");
Auth::routes();


Route::get('/home', function(){
    return redirect("/");
});
// Route::get('/home', 'PublicController@index')->name('home');
