<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryPurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_purchase_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_purchase_id');
            $table->foreign('inventory_purchase_id')->references('id')->on('inventory_purchases');
            $table->unsignedBigInteger('inventory_product_id');
            $table->foreign('inventory_product_id')->references('id')->on('inventory_products');
            $table->integer('quantity');
            $table->double('price_unit');
            $table->double('price_total');
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
        Schema::dropIfExists('inventory_purchase_details');
    }
}
