<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Userdetail;

class UserdetailController extends Controller
{
    //
    public function index(){
      return Userdetail::all();
    }

    public function store(){
      $inputs= Input::all();
      Userdetail::where("uuid",$inputs["uuid"])->delete();
      $inputs["general_infos"]["uuid"]=$inputs["uuid"];
      $inputs["general_infos"]['updated_at']=date("Y-m-d H:i:s");
      $inputs["general_infos"]['created_at']=date("Y-m-d H:i:s");
      Userdetail::Create($inputs["general_infos"]);
      return ["status"=>"success"];
    }
}
