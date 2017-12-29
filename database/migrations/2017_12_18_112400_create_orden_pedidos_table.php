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
//            $table->integer('venta_id')->unsigned()->nullable();
            $table->integer('cliente_id')->unsigned();
            $table->integer('municipio_id')->unsigned();
            $table->string('direccion',140)->nullable();
            $table->integer('numero');
            $table->string('detalle')->nullable();
            $table->date('fechaIngreso');
            $table->date('fechaEntrega')->nullable();
            $table->string('condicionPago')->nullable();
            $table->integer('vendedor_id')->unsigned();
            $table->integer('bodeguero_id')->unsigned()->nullable();
            $table->float('ventasExentas')->nullable()->default(0.00);
            $table->float('ventasGravadas')->nullable();
            $table->float('ventaTotal')->nullable();
            $table->string('rutaArchivo')->default('sin-documento.jpg');
            $table->boolean('procesado')->default(false);
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
