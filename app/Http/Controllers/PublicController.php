<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Websiteinfo;
class PublicController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $websiteinfo = (Websiteinfo::where("key","zh_info")->get())[0]->data; 
        return view('layouts/app_ft')
               ->with("site_info_zh",$websiteinfo);
    }
}
