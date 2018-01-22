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
            $table->float('cantidad');
            $table->float('cantidad_ums');
            $table->integer('unidad_medida_id')->unsigned();
            $table->float('precio_unitario');
            $table->float('precio_unitario_ums',8,5);
            $table->float('venta_exenta');
            $table->float('venta_gravada');
            $table->float('costo_unitario');
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
