<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAjustesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ajustes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('movimiento_id')->unsigned();
            $table->integer('tipo_ajuste_id')->unsigned();
            $table->string('detalle',140);
            $table->date('fechaIngreso');
            $table->integer('realizado_id')->unsigned();
            $table->float('cantidadAnterior');
            $table->float('valorUnitarioAnterior');
            $table->float('cantidadAjuste');
            $table->float('valorUnitarioAjuste');
            $table->float('diferenciaCantidadAjuste');
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
        Schema::dropIfExists('ajustes');
    }
}
