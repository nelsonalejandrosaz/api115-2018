<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleExportacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_exportacions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exportacion_sac_id')->unsigned();
            $table->date('fecha');
            $table->decimal('cargo',14,2);
            $table->decimal('abono',14,2);
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
        Schema::dropIfExists('detalle_exportacions');
    }
}
