<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Userdetail extends Model
{
    //
    protected $fillable=["device_id","uuid","county","member_count","area_size","summer","degree","money","users_id","created_at","updated_at","building_type"];
}
