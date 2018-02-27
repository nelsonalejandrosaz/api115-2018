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
            $table->integer('entrada_id')->unsigned()->nullable();
            $table->integer('salida_id')->unsigned()->nullable();
            $table->integer('ajuste_id')->unsigned()->nullable();
            $table->date('fecha');
            $table->string('detalle')->nullable();
            $table->float('cantidad',12,4);
            $table->float('costo_unitario',12,4);
            $table->float('costo_total',12,4);
            $table->float('cantidad_existencia',12,4)->nullable();
            $table->float('costo_unitario_existencia',12,4)->nullable();
            $table->float('costo_total_existencia',12,4)->nullable();
            $table->dateTime('fecha_procesado')->nullable();
            $table->boolean('procesado')->default(false);
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
