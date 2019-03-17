<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Device;
use App\Devicelog;
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

    public function get_device_cata_summary(){
      $inputs= Input::all();
      $from = date($inputs['start_date']);
      $to = date($inputs['end_date']);
      $result =  DB::table('devicelogs')
                      ->whereBetween('devicelogs.created_at', [$from, $to])
                      ->where("device_consumption","!=",null)
                      
                      ->select("device_id","device_consumption")
                      // ->all()
                      ->leftJoin("devices",function($join){
                        $join->on('devicelogs.device_id',"=","devices.id");
                      })
                      ->groupBy('name')
                      ->selectRaw("device_id,name,sum(device_consumption) as sum,count(device_consumption) as count")
                      ->orderBy('sum','DESC')
                      // ->select("device_id","device_consumption")
                      // ->leftJoin('devices',function($join){
                      //   $join->on('devicelogs.id',"=","devices.id");
                      // })
                      ->get();
      return $result; 
    }

    public function get_device_place_summary(){
      $inputs= Input::all();
      $from = date($inputs['start_date']);
      $to = date($inputs['end_date']);
      $result =  DB::table('devicelogs')
                      ->whereBetween('devicelogs.created_at', [$from, $to])
                      ->where("device_consumption","!=",null)
                      ->select("device_id","device_consumption")
                      // ->all()
                      ->leftJoin("devices",function($join){
                        $join->on('devicelogs.device_id',"=","devices.id");
                      })
                      ->select("place","name","device_id","device_consumption")
                      ->groupBy('place')
                      ->selectRaw("place,name,sum(device_consumption) as sum,count(device_consumption) as count")
                      ->orderBy('place')
                      // ->select("device_id","device_consumption")
                      // ->leftJoin('devices',function($join){
                      //   $join->on('devicelogs.id',"=","devices.id");
                      // })
                      ->get();
      return $result; 
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
