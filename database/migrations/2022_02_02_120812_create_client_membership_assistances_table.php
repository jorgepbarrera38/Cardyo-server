<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientMembershipAssistancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_membership_assistances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_membership_id');
            $table->foreign('client_membership_id')->references('id')->on('client_memberships');
            $table->unsignedBigInteger('turn_id');
            $table->foreign('turn_id')->references('id')->on('turns');
            $table->date('date');
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
        Schema::dropIfExists('client_membership_assistances');
    }
}
