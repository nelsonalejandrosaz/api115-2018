<?php

use App\Configuracion;
use Illuminate\Database\Seeder;

class ConfiguracionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Configuracion::create([
            'iva' => 1.13,
            'comisiones' => 0.05,
            'color_tema' => 'skin-purple',
        ]);
    }
}
