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
            $table->integer('producto_id')->unsigned()->nullable();
            $table->integer('orden_pedido_id')->unsigned()->nullable();
            $table->integer('compra_id')->unsigned()->nullable();
            $table->integer('ajuste_id')->unsigned()->nullable();
            $table->integer('tipo_movimiento_id')->unsigned()->nullable();
            $table->date('fecha');
            $table->string('detalle',140);
            $table->float('cantidadMovimiento',10,2);
            $table->float('valorUnitarioMovimiento',10,2);
            $table->float('valorTotalMovimiento',10,2);
            $table->float('cantidadExistencia',10,2)->nullable();
            $table->float('valorUnitarioExistencia',10,2)->nullable();
            $table->float('valorTotalExistencia',10,2)->nullable();
            $table->boolean('procesado')->nullable();
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
