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
            $table->integer('orden_pedido_id')->unsigned()->nullable();
            $table->integer('produccion_id')->unsigned()->nullable();
            $table->integer('unidad_medida_id')->unsigned();
            $table->integer('precio_id')->unsigned()->nullable();
            $table->float('cantidad',8,3);
            $table->float('precio_unitario',8,4);
            $table->float('venta_exenta',8,4);
            $table->float('venta_gravada',8,4);
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
