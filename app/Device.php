<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
  //
  protected $fillable=["id","name","place","rarely","occasionally","often","frequently","day","default_consumption","consumption_mul","type","created_at","updated_at"];

}