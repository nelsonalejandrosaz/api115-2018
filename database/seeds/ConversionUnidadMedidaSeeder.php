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
            'unidadMedidaOrigen_id' => 2,
            'unidadMedidaDestino_id' => 5,
            'factor' => 1,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Lt-Kg',
            'nombre' => 'Litro a Kilogramo',
            'unidadMedidaOrigen_id' => 5,
            'unidadMedidaDestino_id' => 2,
            'factor' => 1,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Gl-Kg',
            'nombre' => 'Galón a Kilogramo',
            'unidadMedidaOrigen_id' => 4,
            'unidadMedidaDestino_id' => 2,
            'factor' => 3.75,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Kg-Gl',
            'nombre' => 'Kilogramo a Galón',
            'unidadMedidaOrigen_id' => 2,
            'unidadMedidaDestino_id' => 4,
            'factor' => 0.267,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Kg-gr',
            'nombre' => 'Kilogramo a gramo',
            'unidadMedidaOrigen_id' => '2',
            'unidadMedidaDestino_id' => '3',
            'factor' => 1000,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'gr-Kg',
            'nombre' => 'Gramo a Kilogramo',
            'unidadMedidaOrigen_id' => '3',
            'unidadMedidaDestino_id' => '2',
            'factor' => 0.001,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Gl-Lt',
            'nombre' => 'Galón a Litro',
            'unidadMedidaOrigen_id' => 4,
            'unidadMedidaDestino_id' => 5,
            'factor' => 3.75,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Lt-Gl',
            'nombre' => 'Litro a Galón',
            'unidadMedidaOrigen_id' => 4,
            'unidadMedidaDestino_id' => 5,
            'factor' => 0.267,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Kg-Lb',
            'nombre' => 'Kilogramo a Libra',
            'unidadMedidaOrigen_id' => 2,
            'unidadMedidaDestino_id' => 1,
            'factor' => 2.2,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Lb-Kg',
            'nombre' => 'Libra a Kilogramo',
            'unidadMedidaOrigen_id' => 1,
            'unidadMedidaDestino_id' => 2,
            'factor' => 0.454,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Cb1-Kg',
            'nombre' => 'Cubeta 1 a Kilogramo',
            'unidadMedidaOrigen_id' => 8,
            'unidadMedidaDestino_id' => 2,
            'factor' => 17,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Kg-Cb1',
            'nombre' => 'Kilogramo a Cubeta 1',
            'unidadMedidaOrigen_id' => 2,
            'unidadMedidaDestino_id' => 8,
            'factor' => 0.059,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Cb2-Kg',
            'nombre' => 'Cubeta 2 a Kilogramo',
            'unidadMedidaOrigen_id' => 9,
            'unidadMedidaDestino_id' => 2,
            'factor' => 24,
        ]);
        ConversionUnidadMedida::create([
            'codigo' => 'Kg-Cb2',
            'nombre' => 'Kilogramo a Cubeta 2',
            'unidadMedidaOrigen_id' => 2,
            'unidadMedidaDestino_id' => 9,
            'factor' => 0.042,
        ]);
    }
}
