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
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
        Schema::table('cars', function(Blueprint $table){
        	$table->foreign('owner_id')->references('id')->on('users');
        });

        DB::table('cars')->insert([
            [
                'owner_id' => 1,
                'plate' => 'AA111AA',
                'manufacturer' => 'audi',
                'model' => 'a3',
                'year' => '2020-03-20',
                'engine_displacement' => '1600',
                'hp' => '94'
            ],
            [
                'owner_id' => 2,
                'plate' => 'BB222BB',
                'manufacturer' => 'mercedes',
                'model' => 'classe a',
                'year' => '2018-03-20',
                'engine_displacement' => '1500',
                'hp' => '85'
            ],
        ]);

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
