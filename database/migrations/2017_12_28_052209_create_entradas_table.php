<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntradasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entradas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('compra_id')->unsigned()->nullable();
            $table->integer('produccion_id')->unsigned()->nullable();
            $table->float('cantidad');
            $table->float('cantidad_ums');
            $table->integer('unidad_medida_id')->unsigned();
            $table->float('costo_unitario');
            $table->float('costo_unitario_ums');
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
        Schema::dropIfExists('entradas');
    }
}
