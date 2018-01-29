<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('proveedor_id')->unsigned();
            $table->integer('numero')->unsigned();
            $table->string('detalle',140)->nullable();
            $table->date('fecha');
            $table->float('compra_total',8,4)->nullable();
            $table->float('compra_total_con_impuestos',8,4)->nullable();
            $table->integer('ingresado_id')->unsigned()->nullable();
            $table->integer('bodega_id')->unsigned()->nullable();
            $table->string('ruta_archivo')->default('sin-documento.jpg');
            $table->integer('condicion_pago_id')->unsigned();
            $table->integer('estado_compra_id')->default(1);
            $table->float('saldo')->default(0.00);
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
        Schema::dropIfExists('compras');
    }
}
