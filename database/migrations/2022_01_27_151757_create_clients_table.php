<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('identification');
            $table->string('names');
            $table->string('lastname');
            $table->string('full_names');
            $table->enum('sex', ['Masculino', 'Femenino']);
            $table->date('date_of_birth');
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phonenumber')->nullable();
            $table->string('photo')->nullable();
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
        Schema::dropIfExists('clients');
    }
}
