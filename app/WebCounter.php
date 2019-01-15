<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebCounter extends Model
{
	protected $table="web_counter";
    protected $fillable=["webCounter_id","uuid","IP","UPDTTM"];
}
