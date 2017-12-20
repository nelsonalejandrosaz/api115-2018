<?php

use Illuminate\Database\Seeder;

class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Producto::class, 20)->create();
        $productos = App\Producto::all();
        foreach ($productos as $producto) {
            $ids = $producto->id;
            $ids = str_pad($ids, 10,'0',STR_PAD_LEFT);
            $codigo = $producto->TipoProducto->codigo . $ids;
            $producto->codigo = $codigo;
            $producto->save();
        }
    }
}
