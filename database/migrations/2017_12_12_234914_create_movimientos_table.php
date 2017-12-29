<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('producto_id')->unsigned();
            $table->integer('tipo_movimiento_id')->unsigned();
            $table->date('fecha');
            $table->string('detalle',140)->nullable();
            $table->float('cantidadExistencia')->nullable();
            $table->float('costoUnitarioExistencia')->nullable();
            $table->float('costoTotalExistencia')->nullable();
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
        Schema::dropIfExists('movimientos');
    }
}
