<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',140)->unique();
            $table->string('telefono1',25)->nullable();
            $table->string('telefono2',25)->nullable();
            $table->string('direccion',255)->nullable();
            $table->integer('vendedor_id')->unsigned()->nullable();
            $table->string('nit')->nullable();
            $table->string('nrc')->nullable();
            $table->string('nombreContacto')->nullable();
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
        Schema::dropIfExists('clientes');
    }
}
