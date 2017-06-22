<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Devices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices',function($table){

          $table->increments('id')->key();
          $table->string('name')->nullable();
          $table->string('place')->nullable();
          $table->integer('count')->nullable();
          $table->integer('rarely');
          $table->integer('occasionally')->nullable();
          $table->integer('often')->nullable();
          $table->integer('frequently')->nullable();
          $table->integer('day')->nullable();
          $table->integer('default_consumption')->nullable();
          $table->string('consumption_mul')->nullable();
          $table->string('type')->nullable();
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
        Schema::drop('devices');
    }
}
