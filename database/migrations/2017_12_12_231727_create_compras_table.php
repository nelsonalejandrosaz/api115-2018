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
            $table->mediumText('detalle')->nullable();
            $table->date('fecha');
            $table->float('compra_total',12,4)->nullable();
            $table->float('compra_total_con_impuestos',12,4)->nullable();
            $table->integer('ingresado_id')->unsigned()->nullable();
            $table->integer('bodega_id')->unsigned()->nullable();
            $table->string('ruta_archivo')->default('sindocumento.jpg');
            $table->integer('condicion_pago_id')->unsigned();
            $table->integer('estado_compra_id')->default(1);
            $table->float('saldo',12,4)->default(0.00);
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
