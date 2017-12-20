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
//            $table->integer('cliente_id')->unsigned();
//            $table->integer('municipio_id')->unsigned();
            $table->string('numero')->nullable();
            $table->string('detalle')->nullable();
            $table->date('fechaIngreso');
            $table->string('direccion')->nullable();
            $table->integer('ingresadoPor_id')->unsigned();
            $table->float('monto',10,2)->nullable();
            $table->string('rutaArchivo')->default('sin-documento.jpg');
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
