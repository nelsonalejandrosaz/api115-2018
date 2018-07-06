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
            $table->integer('unidad_medida_id')->unsigned();
            $table->float('cantidad',12,4);
            $table->float('costo_unitario',12,4);
            $table->float('costo_total',12,4);
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
