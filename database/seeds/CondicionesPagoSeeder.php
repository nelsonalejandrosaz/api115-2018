<?php

use App\CondicionPago;
use Illuminate\Database\Seeder;

class CondicionesPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CondicionPago::create([
            'codigo' =>'CONTA',
            'nombre' =>'Contado',
        ]);
        CondicionPago::create([
            'codigo' =>'CRE8',
            'nombre' =>'Crédito a 8 dias',
        ]);
        CondicionPago::create([
            'codigo' =>'CRE15',
            'nombre' =>'Crédito a 15 días',
        ]);
        CondicionPago::create([
            'codigo' =>'CRE30',
            'nombre' =>'Crédito a 30 días',
        ]);
    }
}
