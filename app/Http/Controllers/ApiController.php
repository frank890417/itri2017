<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;

class ApiController extends Controller
{
    //
    public function devices(){
      return Device::all();
    }
}
