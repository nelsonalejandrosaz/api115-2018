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
        TipoMovimiento::create(['codigo' => 'ENTC', 'nombre' => 'Entrada']);
        TipoMovimiento::create(['codigo' => 'ENTP', 'nombre' => 'Entrada producción']);
        TipoMovimiento::create(['codigo' => 'SALO', 'nombre' => 'Salida']);
        TipoMovimiento::create(['codigo' => 'SALP', 'nombre' => 'Salida producción']);
        TipoMovimiento::create(['codigo' => 'AJSE', 'nombre' => 'Ajuste Entrada']);
        TipoMovimiento::create(['codigo' => 'AJSS', 'nombre' => 'Ajuste Salida']);
        TipoMovimiento::create(['codigo' => 'AJSC', 'nombre' => 'Ajuste Costo']);
    }
}
