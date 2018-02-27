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
            $table->string('nombre')->unique()->index();
            $table->string('nombre_alternativo')->nullable();
            $table->string('codigo',50)->unique()->nullable();
            $table->float('existencia_min',12,4)->nullable()->default(0.00);
            $table->float('existencia_max',12,4)->nullable()->default(100.00);
            $table->float('cantidad_existencia',12,4)->default(0.000);
            $table->float('cantidad_reserva',12,4)->default(0.000);
            $table->float('costo')->default(0.00);
            $table->string('unidad_factor',50)->nullable();
            $table->float('factor_volumen',12,4)->default(0);
            $table->boolean('producto_activo')->default(true);
            $table->boolean('formula_activa')->default(false);
            $table->timestamps();
            $table->softDeletes();
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
