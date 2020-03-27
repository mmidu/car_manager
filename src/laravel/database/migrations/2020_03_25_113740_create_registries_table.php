<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date');
            $table->string('fiscal_code');
            $table->string('address');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        Schema::table('registries', function(Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users');
        });

        DB::table('registries')->insert([
            [
                'user_id' => 1,
                'first_name' => 'Nome',
                'last_name' => 'Cognome',
                'birth_date' => '1970-01-01',
                'fiscal_code' => 'NMOCGN70A01F704K',
                'address' => 'via strada 12'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registries');
    }
}
