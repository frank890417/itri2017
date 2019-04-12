<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserDetailNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('userdetails',function($table){
            $table->string("uuid")->nullable()->change();
            $table->string("county")->nullable()->change();
            $table->string("member_count")->nullable()->change();
            $table->integer("area_size")->nullable()->change();
            $table->string("summer")->nullable()->change();
            $table->float("degree")->nullable()->change();
            $table->float("money")->nullable()->change();
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
    }
}
