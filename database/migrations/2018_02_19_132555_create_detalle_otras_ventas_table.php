<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleOtrasVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_otras_ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('venta_id')->unsigned();
            $table->mediumText('detalle');
            $table->float('cantidad',12,4);
            $table->float('precio_unitario',12,4);
            $table->float('venta_exenta',12,4);
            $table->float('venta_gravada',12,4);
            $table->float('venta_total',12,4);
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
        Schema::dropIfExists('detalle_otras_ventas');
    }
}
