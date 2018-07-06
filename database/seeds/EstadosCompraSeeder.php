<?php

use App\EstadoCompra;
use Illuminate\Database\Seeder;

class EstadosCompraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EstadoCompra::create([
            'codigo' => 'INGRE',
            'nombre' => 'En verificación',
        ]);
        EstadoCompra::create([
            'codigo' => 'PROCE',
            'nombre' => 'Ingresada',
        ]);
        EstadoCompra::create([
            'codigo' => 'PAGAD',
            'nombre' => 'Pagada',
        ]);
    }
}
