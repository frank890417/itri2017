<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    //
    public function index(){
        return User::all();
    }
    
    public function show($id){
        return User::find($id);
    }
    public function update($id){ 
        $inputs = Input::all();
        $u = User::find($id);

        if (!$u){
            $u = User::create($inputs);
        }else{
            $user = $u  ->update($inputs);
        }
        
        // dd($Advice);
        return [
            "status"=>"success",
            "result"=>User::find($id)
        ] ;
    }
    public function destroy($id){ 
        $u = User::find($id);
        $u -> delete();
    }
    public function create(){
        $inputs = Input::all();
        $user = User::create($inputs);

        // dd($Advice);
        return $user;
    }
}
