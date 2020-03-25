<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transition', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('old_owner_id');
            $table->unsignedBigInteger('new_owner_id');
            $table->unsignedBigInteger('car_id');
            $table->timestamps();
        });
        Schema::table('transition', function(Blueprint $table){
            $table->foreign('old_owner_id')->references('id')->on('users');
            $table->foreign('new_owner_id')->references('id')->on('users');
            $table->foreign('car_id')->references('id')->on('cars');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transition');
    }
}
