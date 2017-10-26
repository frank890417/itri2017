<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Advice;

class AdviceController extends Controller
{
    //
    public function index(){
        return Advice::all();
    }
    
    public function show($id){
        return Advice::find($id);
    }
    public function update($id){ 
        $inputs = Input::all();
        $ad = Advice::find($id);

        if (!$ad){
            $ad = Advice::create($inputs);
        }else{
            $advice = $ad  ->update($inputs);
        }
        
        // dd($Advice);
        return [
            "status"=>"success",
            "result"=>Advice::find($id)
        ] ;
    }
    public function destroy($id){ 
        $ad = Advice::find($id);
        $ad -> delete();
    }
    public function create(){
        $inputs = Input::all();
        $advice = Advice::create($inputs);

        // dd($Advice);
        return $advice;
    }
}
