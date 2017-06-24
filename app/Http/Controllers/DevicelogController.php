<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Devicelog;
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
      return Devicelog::select("uuid",DB::raw("SUM(device_consumption) as total_consumption"))
                      ->groupBy("uuid")
                      ->get();


    }
}
