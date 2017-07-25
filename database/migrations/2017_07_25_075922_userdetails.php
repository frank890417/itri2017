<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Userdetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('userdetails', function (Blueprint $table) {
            $table->increments("id");
            $table->string("uuid");
            $table->string("county");
            $table->string("member_count");
            $table->integer("area_size");
            $table->string("summer");
            $table->float("degree");
            $table->float("money");
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
        Schema::drop('userdetails');
    }
}
