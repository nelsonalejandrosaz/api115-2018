<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salidas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('movimiento_id')->unsigned();
            $table->integer('orden_pedido_id')->unsigned()->nullable();
            $table->integer('produccion_id')->unsigned()->nullable();
            $table->float('cantidad');
            $table->integer('unidad_medida_id')->unsigned();
            $table->float('precioUnitario');
            $table->float('ventaExenta');
            $table->float('ventaGravada');
            $table->float('costoUnitario');
            $table->float('costoTotal');
            $table->boolean('exento')->default(false);
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
        Schema::dropIfExists('salidas');
    }
}
