<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ClientBodyMeasurement;

class DeleteAllColumnsFromClientBodyMeasurements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_body_measurements', function (Blueprint $table) {
            ClientBodyMeasurement::get()->each->delete();
            $table->dropColumn([
                'height',
                'weight',
                'biceps',
                'forearms',
                'shoulders',
                'chest',
                'waist_width',
                'width_at_navel_level',
                'buttocks_or_hip',
                'thigh',
                'calves',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_body_measurements', function (Blueprint $table) {
            $table->double('height')->nullable();//Altura
            $table->double('weight')->nullable();//Peso
            $table->double('biceps')->nullable();//Bíceps
            $table->double('forearms')->nullable();//Antebrazos
            $table->double('shoulders')->nullable();//Hombros
            $table->double('chest')->nullable();//Pecho
            $table->double('waist_width')->nullable();//Anchura cintura
            $table->double('width_at_navel_level')->nullable();//Anchura a nivel del ombligo
            $table->double('buttocks_or_hip')->nullable();//Glúteos o cadera
            $table->double('thigh')->nullable();//Muslo
            $table->double('calves')->nullable();//Pantorrillas
        });
    }
}
