<?php

use App\TipoProducto;
use Illuminate\Database\Seeder;

class TipoProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoProducto::create(['nombre' => 'Materia prima', 'codigo' => 'MP',]);
        TipoProducto::create(['nombre' => 'Producto terminado', 'codigo' => 'PT',]);
        TipoProducto::create(['nombre' => 'Reventa', 'codigo' => 'RV',]);
        TipoProducto::create(['nombre' => 'Materia Prima y Reventa', 'codigo' => 'MR',]);
        TipoProducto::create(['nombre' => 'Producto terminado y Materia Prima', 'codigo' => 'PM',]);
    }
}
