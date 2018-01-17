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
        TipoAjuste::create(['codigo' => 'ENTINI','tipo' => 'ENTRADA' ,'nombre' => 'Inicio de inventario']);
        TipoAjuste::create(['codigo' => 'ENTERI','tipo' => 'ENTRADA' ,'nombre' => 'Error de registro de ingreso']);
        TipoAjuste::create(['codigo' => 'ENTDEV','tipo' => 'ENTRADA' ,'nombre' => 'Devolución del cliente']);
        TipoAjuste::create(['codigo' => 'SALMER','tipo' => 'SALIDA' ,'nombre' => 'Merma']);
        TipoAjuste::create(['codigo' => 'SALACP','tipo' => 'SALIDA' ,'nombre' => 'Accidente en produccion']);
        TipoAjuste::create(['codigo' => 'SALPRV','tipo' => 'SALIDA' ,'nombre' => 'Producto vencido']);
        TipoAjuste::create(['codigo' => 'SALERS','tipo' => 'SALIDA' ,'nombre' => 'Error de registro de salida']);
        TipoAjuste::create(['codigo' => 'COSTO1','tipo' => 'COSTO' ,'nombre' => 'Inicio de inventario']);
        TipoAjuste::create(['codigo' => 'COSTO2','tipo' => 'COSTO' ,'nombre' => 'Error en introducción de costo']);
    }
}
