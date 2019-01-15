<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Websiteinfo;
use Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('home');
        //// view('layouts/app_manage')
        //$websiteinfo = (Websiteinfo::where("key","zh_info")->get())[0]->data; 
        //return view('layouts/app_manage')
        //       ->with("site_info_zh",$websiteinfo)
        //       ->with('user', Auth::user() );
    }

public function manage(){
$user = Auth::user();
if($user->admin){
        $websiteinfo = (Websiteinfo::where("key","zh_info")->get())[0]->data; 
        return view('layouts/app_manage')
               ->with("site_info_zh",$websiteinfo)
               ->with('user', Auth::user() );
}
return view('home');
}
}
