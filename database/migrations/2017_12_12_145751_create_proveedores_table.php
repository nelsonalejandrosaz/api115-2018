<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',50)->unique();
            $table->string('telefono_1',25)->nullable();
            $table->string('telefono_2',25)->nullable();
            $table->string('direccion',140)->nullable();
            $table->string('nit',20)->nullable();
            $table->string('nrc',20)->nullable();
            $table->string('nombre_contacto')->nullable();
            $table->float('saldo',12,4)->default(0.00);
            $table->boolean('nacional')->default(true);
//            $table->boolean('rentencion')->default(false);
            $table->boolean('percepcion')->default(false);
            $table->integer('cuenta_contable')->nullable();
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
        Schema::dropIfExists('proveedores');
    }
}
