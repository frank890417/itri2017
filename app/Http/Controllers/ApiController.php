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

use Illuminate\Support\Facades\File;
use Storage;
use Intervention\Image\ImageManagerStatic as Image;

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
    public function get_device_full_summary(){
      $inputs= Input::all();
      $from = date(array_key_exists('start_date',$inputs)? $inputs['start_date']:"");
      $to = date(array_key_exists('end_date',$inputs)? $inputs['end_date']:"");

      $result =  DB::table('devicelogs');
      if ($from && $to){
        $result = $result->whereBetween('devicelogs.created_at', [$from, $to]);
      }
      $result =  $result
                    ->leftJoin("userdetails",function($join){
                      $join->on('devicelogs.uuid',"=","userdetails.uuid");
                    })
                    ->leftJoin("devices",function($join){
                      $join->on('devicelogs.device_id',"=","devices.id");
                    })
                    ->leftJoin("lights",function($join){
                      $join->on('devicelogs.light_option',"=","lights.id");
                    })
                    ->leftJoin("users",function($join){
                      $join->on('userdetails.users_id',"=","users.id");
                    })
                    ->select("devicelogs.*","devices.name as device_name","devices.place as device_place","userdetails.*","users.email as email","lights.name as light_name","devicelogs.created_at","devicelogs.updated_at")
                    
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

    public function getElecContracter(){
      return DB::table('pb301')->select("id","公司行號名稱","等級","地址")->get();;
    }

    public function getElecMaintenance(){
      return DB::table('pb302')->select("id","公司行號名稱","登記維護範圍","地址")->get();;
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

  public function upload_image(){
    $input = Input::all();
    if(Input::file())
     {


       $image = Input::file('file');
       // $ext = $image->getClientOriginalExtension();
       $filename  =  date('Y_m_d_h_i_s').'_'. $_FILES['file']['name'] ;

       // prevent possible upsizing
       // dd("storage/".$filename);
       $img = Image::make($image);
       $img->resize(1920, null, function ($constraint) {
           $constraint->aspectRatio();
           $constraint->upsize();
       });

       $path = 'img/uploaded/';
       
       $img->save(storage_path('app/'.$path.$filename));
       // dd($img->__toString());
      
       Storage::put($path.$filename,$img->__toString());


       return '/storage/'.$path.str_replace(" ","%20",$filename);
   
           // Image::make($image->getRealPath())->resize(200, 200)->save($path);
           // $user->image = $filename;
           // $user->save();
    }
}
}
