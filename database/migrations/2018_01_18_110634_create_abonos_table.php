<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbonosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abonos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('venta_id')->unsigned();
            $table->integer('cliente_id')->unsigned();
            $table->integer('recibo_caja')->nullable();
            $table->date('fecha');
            $table->mediumText('detalle')->nullable();
            $table->float('cantidad',10,2);
            $table->integer('forma_pago_id')->unsigned()->default(1);
            $table->integer('tipo_abono_id')->unsigned()->default(1);
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
        Schema::dropIfExists('abonos');
    }
}
