<?php

use App\EstadoOrdenPedido;
use Illuminate\Database\Seeder;

class EstadosOrdenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EstadoOrdenPedido::create([
            'codigo' => 'SP',
            'nombre' => 'Sin procesar',
        ]);
        EstadoOrdenPedido::create([
            'codigo' => 'PR',
            'nombre' => 'Procesada',
        ]);
        EstadoOrdenPedido::create([
            'codigo' => 'FC',
            'nombre' => 'Facturada',
        ]);
    }
}
