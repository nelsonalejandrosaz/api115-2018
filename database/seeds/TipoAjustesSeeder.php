<?php

use App\TipoAjuste;
use Illuminate\Database\Seeder;

class TipoAjustesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoAjuste::create(['codigo' => 'ENTINI', 'nombre' => 'Inicio de inventario']);
        TipoAjuste::create(['codigo' => 'ENTERI', 'nombre' => 'Error de registro de ingreso']);
        TipoAjuste::create(['codigo' => 'ENTDEV', 'nombre' => 'Devolución del cliente']);
        TipoAjuste::create(['codigo' => 'SALMER', 'nombre' => 'Merma']);
        TipoAjuste::create(['codigo' => 'SALACP', 'nombre' => 'Accidente en produccion']);
        TipoAjuste::create(['codigo' => 'SALPRV', 'nombre' => 'Producto vencido']);
        TipoAjuste::create(['codigo' => 'SALERS', 'nombre' => 'Error de registro de salida']);
    }
}
