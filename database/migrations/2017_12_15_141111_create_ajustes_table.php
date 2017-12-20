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
            $table->date('fechaIngreso');
            $table->integer('realizadoPor_id')->unsigned();
            $table->float('cantidadAnterior',10,2);
            $table->float('valorUnitarioAnterior',10,2);
            $table->float('cantidadAjuste',10,2);
            $table->float('valorUnitarioAjuste',10,2);
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
