<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
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
            $table->integer('municipio_id')->default(1);
            $table->string('nombre')->unique();
            $table->string('nombre_alternativo')->nullable();
            $table->string('telefono_1',25)->nullable();
            $table->string('telefono_2',25)->nullable();
            $table->string('direccion',255)->nullable();
            $table->string('correo',100)->nullable();
            $table->integer('vendedor_id')->unsigned()->nullable();
            $table->string('nit')->nullable();
            $table->string('nrc')->nullable();
            $table->string('giro')->nullable();
            $table->string('nombre_contacto')->nullable();
            $table->float('saldo',12,4)->default(0.00);
            $table->boolean('retencion')->default(false);
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
