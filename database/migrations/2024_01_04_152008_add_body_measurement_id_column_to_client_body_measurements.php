<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBodyMeasurementIdColumnToClientBodyMeasurements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_body_measurements', function (Blueprint $table) {
            $table->unsignedBigInteger('body_measurement_id')->after('client_id');
            $table->foreign('body_measurement_id')->references('id')->on('body_measurements');
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
            //
        });
    }
}
