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
        UnidadMedida::create(['nombre' => 'Libras', 'abreviatura' => 'Lb',]);
        UnidadMedida::create(['nombre' => 'Kilogramos', 'abreviatura' => 'Kg',]);
        UnidadMedida::create(['nombre' => 'Gramos', 'abreviatura' => 'gr',]);
        // Volumen
        UnidadMedida::create(['nombre' => 'Galón', 'abreviatura' => 'Gl',]);
        UnidadMedida::create(['nombre' => 'Litro', 'abreviatura' => 'Lt',]);
        UnidadMedida::create(['nombre' => 'Metros cúbicos', 'abreviatura' => 'm3',]);
        // Otros
        UnidadMedida::create(['nombre' => 'Unidad', 'abreviatura' => 'Und',]);
        UnidadMedida::create(['nombre' => 'Otros', 'abreviatura' => 'otro',]);
    }
}
