<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('unidad_medida_id')->unsigned();
            $table->integer('tipo_producto_id')->unsigned();
            $table->integer('categoria_id')->unsigned();
            $table->string('nombre')->unique();
            $table->string('codigo')->unique()->nullable();
            $table->float('existenciaMin',8,2)->default(0.00);
            $table->float('existenciaMax',8,2)->default(1000.00);
            $table->float('cantidadExistencia')->default(0.00);
            $table->float('precioCompra')->default(0.00);
            $table->float('precioVenta')->default(0.00);
            $table->float('margenGanancia')->default(0.00);
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
        Schema::dropIfExists('productos');
    }
}
