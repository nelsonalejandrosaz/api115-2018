<?php

use App\Rol;
use Illuminate\Database\Seeder;

class RolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rol::create(['nombre' => 'Administrador', 'descripcion' => 'Administrador del sistema']);
        Rol::create(['nombre' => 'Vendedor', 'descripcion' => 'Encargado de ventas']);
        Rol::create(['nombre' => 'Bodeguero', 'descripcion' => 'Encargado de bodega']);
    }
}
