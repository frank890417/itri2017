<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;
class DeviceController extends Controller
{
    //
    public function index(){
        return Device::all();
    }
    
    public function show($id){
        return Device::find($id);
    }
}
