<?php

use App\TipoMovimiento;
use Illuminate\Database\Seeder;

class TipoMovimientosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoMovimiento::create(['codigo' => 'ENTRADA', 'nombre' => 'Entrada']);
        TipoMovimiento::create(['codigo' => 'SALIDA', 'nombre' => 'Salida']);
        TipoMovimiento::create(['codigo' => 'AJSTENT', 'nombre' => 'Ajuste Entrada']);
        TipoMovimiento::create(['codigo' => 'AJSTSAL', 'nombre' => 'Ajuste Salida']);
    }
}
