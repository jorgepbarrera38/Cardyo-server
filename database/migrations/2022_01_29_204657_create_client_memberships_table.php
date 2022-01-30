<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_memberships', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->unsignedBigInteger('turn_id');
            $table->foreign('turn_id')->references('id')->on('turns');
            $table->unsignedBigInteger('membership_type_id');
            $table->foreign('membership_type_id')->references('id')->on('membership_types');
            $table->integer('quantity');
            $table->double('price');
            $table->double('total_price');
            $table->date('date_start');
            $table->date('date_finish');
            $table->text('observations')->nullable();
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
        Schema::dropIfExists('client_memberships');
    }
}
