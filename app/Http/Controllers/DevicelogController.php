<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Devicelog;
use App\Userdetail;
class DevicelogController extends Controller
{
    //
    public function index(){
      return Devicelog::all();
    }

    public function store(){
      $inputs= Input::all();
      Devicelog::where("uuid",$inputs["uuid"])->delete();
      foreach ($inputs["user_data"] as $value) {
        $value['updated_at']=date("Y-m-d H:i:s");
        $value['created_at']=date("Y-m-d H:i:s");
        $news = Devicelog::Create($value);
      }
      return ["status"=>"success"];
    }

    public function show_uuid_group(){
      return Devicelog::select(
        "uuid",
        DB::raw("SUM(device_consumption) as total_consumption"),
        DB::raw("MAX(updated_at) as created_time"),
        DB::raw("COUNT(device_consumption) as device_count")
        )
        ->groupBy("uuid")
        ->get();
    }

    public function show_uuid_group_detail($uuid){
      $result = [ 
        "logs" => Devicelog::where("uuid",$uuid)->get(),
        "user" => Userdetail::where("uuid",$uuid)->first(),
      ];

      return $result;

    }
}
