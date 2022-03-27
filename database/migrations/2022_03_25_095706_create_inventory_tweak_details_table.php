<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTweakDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_tweak_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_tweak_id');
            $table->foreign('inventory_tweak_id')->references('id')->on('inventory_tweaks');
            $table->unsignedBigInteger('inventory_product_id');
            $table->foreign('inventory_product_id')->references('id')->on('inventory_products');
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
        Schema::dropIfExists('inventory_tweak_details');
    }
}
