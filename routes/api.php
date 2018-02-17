<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/devicelog/uuid","DevicelogController@show_uuid_group");
Route::get("/devicelog/{uuid}","DevicelogController@show_uuid_group_detail");
Route::resource("devices","DeviceController");


Route::get("/advice_devices/{cata}","ApiController@get_advice_devices_by_cata");
Route::get("devices","ApiController@devices");
Route::resource("advices","AdviceController");

Route::get("userdetails","ApiController@userdetails");
Route::get("/websiteinfo/key/{key}","ApiController@websiteinfo");
Route::post("/websiteinfo/key/{key}","ApiController@websiteinfo_save");


// Route::resource("devices","ApiController");