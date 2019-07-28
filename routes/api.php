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
//Route::get('email-verification/error', 'Auth\RegisterController@getVerificationError')->name('email-verification.error');
//Route::get('email-verification/check/{token}', 'Auth\RegisterController@getVerification')->name('email-verification.check');

Route::get("/devicelog/uuid","DevicelogController@show_uuid_group");
Route::get("/devicelog/{uuid}","DevicelogController@show_uuid_group_detail");
Route::resource("devices","DeviceController");

Route::get("/advice_devices/{cata}","ApiController@get_advice_devices_by_cata");
Route::get("devices","ApiController@devices");
Route::resource("advices","AdviceController");

Route::get("userdetails","ApiController@userdetails");
Route::get("userdetails/{users_id}","ApiController@userdetails_by_users_id");
Route::get("/websiteinfo/key/{key}","ApiController@websiteinfo");
Route::post("/websiteinfo/key/{key}","ApiController@websiteinfo_save");
Route::get("/device/summary","ApiController@get_device_cata_summary");
Route::get("/place/summary","ApiController@get_device_place_summary");
Route::get("/device/summary/full","ApiController@get_device_full_summary");

//authenticate issues
Route::get("users","ApiController@users");
Route::get("devicelogLast/{users_id}","DevicelogController@show_last_devicelog_by_users_id");
Route::get("devicesLast/{users_id}","DevicelogController@show_last_devices_by_users_id");
Route::get("user_admin/{users_id}","ApiController@user_admin");

Route::post('upload',"ApiController@upload_image");
Route::resource('page',"PageController");
// Route::resource("devices","ApiController");