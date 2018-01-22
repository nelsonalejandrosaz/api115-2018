<?php

use App\ConversionUnidadMedida;
use Illuminate\Database\Seeder;

class ConversionUnidadMedidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ConversionUnidadMedida::create([
            'codigo' => 'Kg-Lt',
            'nombre' => 'Kilogramo a Litro',
            'unidad_medida_origen_id' => 2,
            'unidad_medida_destino_id' => 5,
            'factor' => 1,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Lt-Kg',
            'nombre' => 'Litro a Kilogramo',
            'unidad_medida_origen_id' => 5,
            'unidad_medida_destino_id' => 2,
            'factor' => 1,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Gl-Kg',
            'nombre' => 'Galón a Kilogramo',
            'unidad_medida_origen_id' => 4,
            'unidad_medida_destino_id' => 2,
            'factor' => 3.75,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Kg-Gl',
            'nombre' => 'Kilogramo a Galón',
            'unidad_medida_origen_id' => 2,
            'unidad_medida_destino_id' => 4,
            'factor' => 0.267,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Kg-gr',
            'nombre' => 'Kilogramo a gramo',
            'unidad_medida_origen_id' => '2',
            'unidad_medida_destino_id' => '3',
            'factor' => 1000,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'gr-Kg',
            'nombre' => 'Gramo a Kilogramo',
            'unidad_medida_origen_id' => '3',
            'unidad_medida_destino_id' => '2',
            'factor' => 0.001,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Gl-Lt',
            'nombre' => 'Galón a Litro',
            'unidad_medida_origen_id' => 4,
            'unidad_medida_destino_id' => 5,
            'factor' => 3.75,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Lt-Gl',
            'nombre' => 'Litro a Galón',
            'unidad_medida_origen_id' => 4,
            'unidad_medida_destino_id' => 5,
            'factor' => 0.267,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Kg-Lb',
            'nombre' => 'Kilogramo a Libra',
            'unidad_medida_origen_id' => 2,
            'unidad_medida_destino_id' => 1,
            'factor' => 2.2,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Lb-Kg',
            'nombre' => 'Libra a Kilogramo',
            'unidad_medida_origen_id' => 1,
            'unidad_medida_destino_id' => 2,
            'factor' => 0.454,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Cb1-Kg',
            'nombre' => 'Cubeta 1 a Kilogramo',
            'unidad_medida_origen_id' => 8,
            'unidad_medida_destino_id' => 2,
            'factor' => 17,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Kg-Cb1',
            'nombre' => 'Kilogramo a Cubeta 1',
            'unidad_medida_origen_id' => 2,
            'unidad_medida_destino_id' => 8,
            'factor' => 0.059,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Cb2-Kg',
            'nombre' => 'Cubeta 2 a Kilogramo',
            'unidad_medida_origen_id' => 9,
            'unidad_medida_destino_id' => 2,
            'factor' => 24,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Kg-Cb2',
            'nombre' => 'Kilogramo a Cubeta 2',
            'unidad_medida_origen_id' => 2,
            'unidad_medida_destino_id' => 9,
            'factor' => 0.042,
        ]);
    }
}
