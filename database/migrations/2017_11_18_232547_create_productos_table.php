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
            $table->string('nombre',140)->unique()->index();
            $table->string('nombre_alternativo',140)->nullable();
            $table->string('codigo',50)->unique()->nullable();
            $table->float('existencia_min',8,2)->nullable()->default(0.00);
            $table->float('existencia_max',8,2)->nullable()->default(500.00);
            $table->float('cantidad_existencia')->default(0.00);
            $table->float('costo')->default(0.00);
            $table->float('factor_volumen',8,3)->nullable();
            $table->boolean('producto_activo')->default(true);
            $table->boolean('formula_activa')->default(false);
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
