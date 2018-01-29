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
        UnidadMedida::create(['nombre' => 'Libras', 'abreviatura' => 'Lb', 'tipo' => 'Masa']);
        UnidadMedida::create(['nombre' => 'Kilogramos', 'abreviatura' => 'Kg', 'tipo' => 'Masa']);
        UnidadMedida::create(['nombre' => 'Gramos', 'abreviatura' => 'gr', 'tipo' => 'Masa']);
        // Volumen
        UnidadMedida::create(['nombre' => 'Galón', 'abreviatura' => 'Gl', 'tipo' => 'Volumen']);
        UnidadMedida::create(['nombre' => 'Litro', 'abreviatura' => 'Lt', 'tipo' => 'Volumen']);
        UnidadMedida::create(['nombre' => 'Metros cúbicos', 'abreviatura' => 'm3', 'tipo' => 'Volumen']);
        // Otros
        UnidadMedida::create(['nombre' => 'Unidad', 'abreviatura' => 'Und', 'tipo' => 'Unidad']);
        UnidadMedida::create(['nombre' => 'Cubeta 1', 'abreviatura' => 'Cub1', 'tipo' => 'Otros']);
        UnidadMedida::create(['nombre' => 'Cubeta 2', 'abreviatura' => 'Cub2', 'tipo' => 'Otros']);
        UnidadMedida::create(['nombre' => 'Otros', 'abreviatura' => 'otro', 'tipo' => 'Otros']);
    }
}
