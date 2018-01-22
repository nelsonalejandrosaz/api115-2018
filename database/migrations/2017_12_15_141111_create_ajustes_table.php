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
            $table->integer('tipo_ajuste_id')->unsigned();
            $table->string('detalle',140);
            $table->date('fecha');
            $table->integer('realizado_id')->unsigned();
            $table->float('cantidad_anterior');
            $table->float('valor_unitario_anterior');
            $table->float('cantidad_ajuste')->nullable();
            $table->float('valor_unitario_ajuste')->nullable();
            $table->float('diferencia_cantidad_ajuste');
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
