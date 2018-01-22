<?php

use App\EstadoVenta;
use Illuminate\Database\Seeder;

class EstadosVentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EstadoVenta::create([
            'codigo' => 'PP',
            'nombre' => 'Pendiente de pago',
        ]);
        EstadoVenta::create([
            'codigo' => 'PG',
            'nombre' => 'Pagado',
        ]);
        EstadoVenta::create([
            'codigo' => 'AN',
            'nombre' => 'Anulada',
        ]);
    }
}
