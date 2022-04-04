<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->double('total');
            $table->enum('status', ['Confirmado', 'Anulado'])->default('Confirmado');
            $table->unsignedBigInteger('user_create_id');
            $table->foreign('user_create_id')->references('id')->on('users');
            $table->unsignedBigInteger('user_delete_id')->nullable();
            $table->foreign('user_delete_id')->references('id')->on('users');
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
        Schema::dropIfExists('sales');
    }
}
