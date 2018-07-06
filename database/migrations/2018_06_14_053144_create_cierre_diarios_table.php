<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCierreDiariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cierre_muensual', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->string('detalle',160);
            $table->decimal('inventario_inicial',16,2);
            $table->decimal('inventario_final',16,2);
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
        Schema::dropIfExists('cierre_diarios');
    }
}
