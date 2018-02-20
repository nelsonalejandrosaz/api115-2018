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
            'nombre' => 'Sin despachar',
        ]);
        EstadoOrdenPedido::create([
            'codigo' => 'PR',
            'nombre' => 'Despachada',
        ]);
        EstadoOrdenPedido::create([
            'codigo' => 'FC',
            'nombre' => 'Facturada',
        ]);
        EstadoOrdenPedido::create([
            'codigo' => 'AN',
            'nombre' => 'Anulada',
        ]);
    }
}
