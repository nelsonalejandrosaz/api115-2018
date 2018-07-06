<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProduccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bodega_id')->unsigned();
            $table->integer('formula_id')->unsigned();
            $table->integer('producto_id')->unsigned()->nullable();
            $table->float('cantidad',16,4);
            $table->date('fecha');
            $table->integer('lote')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->mediumText('detalle')->nullable();
            $table->boolean('procesado')->default(false);
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
        Schema::dropIfExists('producciones');
    }
}
