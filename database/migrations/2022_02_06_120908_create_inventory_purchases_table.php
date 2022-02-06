<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_provider_id');
            $table->foreign('inventory_provider_id')->references('id')->on('inventory_providers');
            $table->string('bill');
            $table->date('date_bill');
            $table->string('attachment')->nullable();
            $table->text('observations');
            $table->double('total');
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
        Schema::dropIfExists('inventory_purchases');
    }
}
