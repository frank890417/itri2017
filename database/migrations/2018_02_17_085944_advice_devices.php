<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdviceDevices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('advice_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('itemid')->nullable();
            $table->string('company')->nullable();
            $table->json('infos')->nullable();
            $table->string('cata')->nullable();
            $table->string('size')->nullable();
            $table->string('link',1000)->nullable();
            $table->string('consumption')->nullable();
            // $table->text('data');
            $table->timestamps();
            //
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

        Schema::drop('advice_devices');
    }
}
