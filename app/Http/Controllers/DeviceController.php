<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
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
    public function update($id){ 
        $inputs = Input::all();
        $device = 
            Device::find($id)
            ->update($inputs);

        // dd($device);
        return [
            "status"=>"success",
            "result"=>Device::find($id)
        ] ;
    }
}
