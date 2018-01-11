<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tipo_documento_id')->unsigned();
            $table->string('numero')->nullable();
            $table->date('fechaIngreso');
            $table->integer('vendedor_id')->unsigned();
            $table->string('nit',16)->nullable();
            $table->string('nrc')->nullable();
            $table->string('rutaArchivo')->default('sin-documento.jpg');
            $table->string('procesado')->default(false);
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
        Schema::dropIfExists('ventas');
    }
}
