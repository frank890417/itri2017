<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Device;
use App\Advice;
use App\Websiteinfo;
use App\Userdetail;
use App\AdviceDevice;
use App\User;

class ApiController extends Controller
{
    //
    public function devices(){
      return Device::all();
    }
    //
    public function advices(){
      return Advice::all();
    }

    public function get_advice_devices_by_cata($cata){
      return AdviceDevice::where("cata",$cata)->get();
    }

    public function userdetails(){
      return Userdetail::all();
    }
	public function userdetails_by_users_id($users_id){
      return Userdetail::where("users_id",$users_id)->orderBy('updated_at', 'desc')->first();
    }

    public function websiteinfo($key){
      return Websiteinfo::where("key",$key)->first()->data;
    }

    public function websiteinfo_save($key){
      $input = Input::all();
      // dd($input);
      $websiteinfo = Websiteinfo::where("key",$key)->first();
      $websiteinfo->data=json_encode($input);
      $websiteinfo->save();
    }

    public function users(){
      return User::all();
    }

    public function user_admin($id){
      $u= User::find($id);
      return $u->admin;
  }
}
