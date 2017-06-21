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
        Schema::create('posts',function($table){

          $table->increments('id')->nullable();
          $table->string('uuid')->nullable();
          $table->integer('device_id',1000)->nullable();
          $table->integer('count')->nullable();
          $table->integer('device_consumption')->nullable();
          $table->integer('buy_time_option')->nullable();
          $table->integer('light_option')->nullable();
          $table->integer('option')->nullable();
          $table->integer('place_id')->nullable();

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
        Schema::drop('posts');
    }
}
