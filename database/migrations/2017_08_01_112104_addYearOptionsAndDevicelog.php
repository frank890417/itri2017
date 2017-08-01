<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddYearOptionsAndDevicelog extends Migration
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
            $table->string("year_options")->default("[3,5,10]");
            $table->integer("old_condition")->default(-1);
        });
        Schema::table('devicelogs',function($table){
            $table->string('buy_time')->nullable()->change();
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
        Schema::table('devices', function($table) {
            $table->dropColumn("year_options");
            $table->dropColumn("old_condition");
        });
        Schema::table('devicelogs', function($table) {
            $table->integer('buy_time')->nullable()->change();
        });
    }
}
