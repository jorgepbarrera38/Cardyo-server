<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;

class CreateBodyMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('body_measurements', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        //Permisos de medidas corporales
        Permission::create([
            'name' => 'body_measurements.index',
            'description' => 'Listar medidas corporales'
        ]);
        Permission::create([
            'name' => 'body_measurements.store',
            'description' => 'Registrar medidas corporales'
        ]);
        Permission::create([
            'name' => 'body_measurements.update',
            'description' => 'Actualizar medidas corporales'
        ]);
        Permission::create([
            'name' => 'body_measurements.delete',
            'description' => 'Eliminar medidas corporales'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('body_measurements');
    }
}
