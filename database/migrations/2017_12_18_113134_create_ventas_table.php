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
            $table->integer('orden_pedido_id')->unsigned();
            $table->integer('estado_venta_id')->unsigned();
            $table->string('numero')->nullable();
            $table->date('fecha');
            $table->integer('vendedor_id')->unsigned();
            $table->float('venta_total_con_impuestos',8,4)->nullable();
            $table->string('ruta_archivo')->default('sin-documento.jpg');
            $table->float('saldo',8,4)->default(0.00);
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
