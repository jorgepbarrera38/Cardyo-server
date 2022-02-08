<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryPurchaseReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_purchase_return_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inv_purchase_return_id');
            $table->foreign('inv_purchase_return_id')->references('id')->on('inventory_purchase_returns');
            $table->unsignedBigInteger('inv_purchase_detail_id');
            $table->foreign('inv_purchase_detail_id')->references('id')->on('inventory_purchase_details');
            $table->integer('quantity');
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
        Schema::dropIfExists('inventory_purchase_return_details');
    }
}
