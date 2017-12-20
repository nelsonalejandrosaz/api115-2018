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
            $table->date('fechaIngreso');
            $table->float('monto',10,2)->nullable();
            $table->integer('ingresadoPor_id')->unsigned()->nullable();
            $table->integer('revisadoPor_id')->unsigned()->nullable();
            $table->string('rutaArchivo')->default('sin-documento.jpg');
            $table->boolean('revisado')->nullable();
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
