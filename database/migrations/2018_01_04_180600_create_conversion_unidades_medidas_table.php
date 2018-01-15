<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversionUnidadesMedidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversion_unidades_medidas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo',15)->unique();
            $table->string('nombre');
            $table->integer('unidadMedidaOrigen_id')->unsigned();
            $table->integer('unidadMedidaDestino_id')->unsigned();
            $table->float('factor',8,3);
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
        Schema::dropIfExists('conversion_unidades_medidas');
    }
}
