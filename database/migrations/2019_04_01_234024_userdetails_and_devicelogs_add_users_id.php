<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserdetailsAndDevicelogsAddUsersId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('devicelogs',function($table){
            $table->integer('users_id')->nullable();
        });
        Schema::table('userdetails',function($table){
            $table->integer('users_id')->nullable();
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
        Schema::table('devicelogs',function($table){
            $table->removeColumn('users_id');
        });
        Schema::table('userdetails',function($table){
            $table->removeColumn('users_id');
        });
    }
}
