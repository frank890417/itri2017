<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\WebCounter;
class WebCounterController extends Controller
{
    public function index(){
      return WebCounter::all();
    }

    public function store(){
      $inputs= Input::all();
      //Userdetail::where("uuid",$inputs["uuid"])->delete();
      $inputs["IP"]=getenv('REMOTE_ADDR');
      WebCounter::Create($inputs);
      return ["status"=>"success"];
    }
}
