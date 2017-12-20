<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdenPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_pedidos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('factura_id')->unsigned()->nullable();
            $table->integer('produccion_id')->unsigned()->nullable();
            $table->integer('cliente_id')->unsigned();
            $table->integer('municipio_id')->unsigned();
            $table->string('direccion',140)->nullable();
            $table->integer('numero')->nullable();
            $table->string('detalle')->nullable();
            $table->date('fechaIngreso');
            $table->date('fechaEntrega')->nullable();
            $table->integer('ingresadoPor_id')->unsigned();
            $table->integer('revisadoPor_id')->unsigned()->nullable();
            $table->string('rutaArchivo')->default('sin-documento.jpg');
            $table->boolean('revisado')->default(false);
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
        Schema::dropIfExists('orden_pedidos');
    }
}
