<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Devicelogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('devicelogs',function($table){

          $table->increments('id');
          $table->string('uuid');
          $table->integer('device_id')->nullable();
          $table->integer('count')->nullable();
          $table->integer('device_consumption')->nullable();
          $table->integer('hour_consumption')->nullable();
          $table->integer('buy_time_option')->nullable();
          $table->integer('light_option')->nullable();
          $table->integer('option')->nullable();
          $table->integer('place_id')->nullable();
          $table->timestamps(); 

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('devicelogs');
    }
}
