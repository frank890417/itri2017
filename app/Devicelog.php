<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devicelog extends Model
{
    //
    protected $fillable=["device_id","uuid","count","device_consumption","hour_consumption","buy_time_option","light_option","option","place_id"];
}
