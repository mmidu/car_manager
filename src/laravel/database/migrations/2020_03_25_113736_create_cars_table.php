<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            $table->string('plate')->unique();
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->date('year')->nullable();
            $table->string('engine_displacement')->nullable();
            $table->string('hp')->nullable();
        });
        Schema::table('cars', function(Blueprint $table){
        	$table->foreign('owner_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
