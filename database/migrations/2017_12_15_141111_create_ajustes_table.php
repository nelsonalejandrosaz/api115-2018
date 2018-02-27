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
            $table->mediumText('detalle');
            $table->date('fecha');
            $table->integer('realizado_id')->unsigned();
            $table->float('cantidad_anterior',12,4);
            $table->float('valor_unitario_anterior',12,4);
            $table->float('cantidad_ajuste',12,4)->nullable();
            $table->float('valor_unitario_ajuste',12,4)->nullable();
            $table->float('diferencia_ajuste',12,4);
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
