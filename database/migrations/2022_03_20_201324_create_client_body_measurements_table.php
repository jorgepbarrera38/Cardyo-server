<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientBodyMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_body_measurements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->date('date');
            $table->double('height');//Altura
            $table->double('weight');//Peso
            $table->double('biceps');//Bíceps
            $table->double('forearms');//Antebrazos
            $table->double('shoulders');//Hombros
            $table->double('chest');//Pecho
            $table->double('waist_width');//Anchura cintura
            $table->double('width_at_navel_level');//Anchura a nivel del ombligo
            $table->double('buttocks_or_hip');//Glúteos o cadera
            $table->double('thigh');//Muslo
            $table->double('calves');//Pantorrillas
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
        Schema::dropIfExists('client_body_measurements');
    }
}
