<?php

use App\UnidadMedida;
use Illuminate\Database\Seeder;

class UnidadMedidasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Masa
        UnidadMedida::create(['nombre' => 'Libras', 'abreviatura' => 'lb',]);
        UnidadMedida::create(['nombre' => 'Kilogramos', 'abreviatura' => 'kg',]);
        UnidadMedida::create(['nombre' => 'Gramos', 'abreviatura' => 'g',]);
        // Volumen
        UnidadMedida::create(['nombre' => 'Galones', 'abreviatura' => 'gal',]);
        UnidadMedida::create(['nombre' => 'Litros', 'abreviatura' => 'l',]);
        UnidadMedida::create(['nombre' => 'Metros cúbicos', 'abreviatura' => 'm3',]);
        // Otros
        UnidadMedida::create(['nombre' => 'Unidades', 'abreviatura' => 'u',]);
        UnidadMedida::create(['nombre' => 'Otros', 'abreviatura' => 'otro',]);
    }
}
