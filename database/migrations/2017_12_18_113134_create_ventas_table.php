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
            $table->integer('orden_pedido_id')->unsigned()->nullable();
            $table->integer('estado_venta_id')->unsigned();
            $table->integer('cliente_id')->unsigned()->nullable();
            $table->integer('vendedor_id')->unsigned()->nullable();
            $table->integer('condicion_pago_id')->unsigned()->nullable();
            $table->string('numero')->nullable();
            $table->date('fecha');
            $table->float('venta_total', 12, 4)->nullable();
            $table->float('venta_total_con_impuestos', 12, 4)->nullable();
            $table->float('suma', 12, 4)->nullable();
            $table->float('flete', 12, 4)->nullable();
            $table->string('ruta_archivo')->default('sindocumento.jpg');
            $table->float('saldo', 12, 4)->default(0.00);
            $table->date('fecha_anulado')->nullable();
            $table->date('fecha_liquidado')->nullable();
            $table->boolean('comision')->default(false);
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
