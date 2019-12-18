<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccAddFeature extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('devices',function($table){
            $table->float("default_area_size")->default(0)->nullable();
            $table->float("default_ac_power")->default(0)->nullable();
            $table->float("default_cspf")->default(0)->nullable();
        });
        Schema::table('devicelogs',function($table){
            $table->float("area_size")->default(0)->nullable();
            $table->float("ac_power")->default(0)->nullable();
            $table->float("cspf")->default(0)->nullable();
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
        Schema::table('devices',function($table){
            $table->dropColumn("default_area_size");
            $table->dropColumn("default_ac_power");
            $table->dropColumn("default_cspf");
        });
        Schema::table('devicelogs',function($table){
            $table->dropColumn("area_size");
            $table->dropColumn("ac_power");
            $table->dropColumn("cspf");
        });
    }
}
