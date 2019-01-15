<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\MemberRec;
class MemberRecController extends Controller
{
    public function index(){
      //return MemberRec::all();
      return view('/pages/memberrec');
    }

    public function store(){
	  $inputs= Input::all();
	  //return ["status"=>"success"];
      return view('/pages/memberrec',["uuid_value"=>$inputs["uuid"]]);
    } 
}