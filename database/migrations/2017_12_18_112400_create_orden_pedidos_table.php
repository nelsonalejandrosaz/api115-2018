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
            $table->integer('cliente_id')->unsigned();
            $table->integer('numero');
            $table->string('detalle',140)->nullable();
            $table->date('fecha');
            $table->dateTime('fecha_procesado')->nullable();
            $table->date('fecha_entrega')->nullable();
            $table->integer('condicion_pago_id')->unsigned()->nullable();
            $table->integer('vendedor_id')->unsigned();
            $table->integer('bodega_id')->unsigned()->nullable();
            $table->float('ventas_exentas',8,4)->nullable()->default(0.00);
            $table->float('ventas_gravadas',8,4)->nullable();
            $table->float('venta_total',8,4)->nullable();
            $table->string('ruta_archivo')->default('sin-documento.jpg');
            $table->integer('estado_id')->default(0);
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
