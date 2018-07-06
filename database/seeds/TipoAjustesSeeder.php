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
        TipoAjuste::create(['codigo' => 'ENTANU','tipo' => 'ENTRADA' ,'nombre' => 'Anulación de documento']);
        TipoAjuste::create(['codigo' => 'ENTAJS','tipo' => 'ENTRADA' ,'nombre' => 'Ajuste de sistema con inventario físico']);
        TipoAjuste::create(['codigo' => 'SALMER','tipo' => 'SALIDA' ,'nombre' => 'Merma']);
        TipoAjuste::create(['codigo' => 'SALPRV','tipo' => 'SALIDA' ,'nombre' => 'Producto vencido']);
        TipoAjuste::create(['codigo' => 'SALERS','tipo' => 'SALIDA' ,'nombre' => 'Error de registro de salida']);
        TipoAjuste::create(['codigo' => 'SALAJS','tipo' => 'SALIDA' ,'nombre' => 'Ajuste de sistema con inventario físico']);
        TipoAjuste::create(['codigo' => 'COSTO1','tipo' => 'COSTO' ,'nombre' => 'Inicio de inventario']);
        TipoAjuste::create(['codigo' => 'COSTO2','tipo' => 'COSTO' ,'nombre' => 'Error en introducción de costo']);
        TipoAjuste::create(['codigo' => 'ENTPRO','tipo' => 'ENTRADA' ,'nombre' => 'Entrada para producción']);
        TipoAjuste::create(['codigo' => 'ENTBON','tipo' => 'ENTRADA' ,'nombre' => 'Traspaso a código de bonificación ']);
        TipoAjuste::create(['codigo' => 'ENTERP','tipo' => 'ENTRADA' ,'nombre' => 'Error en producción']);
        TipoAjuste::create(['codigo' => 'SALBON','tipo' => 'SALIDA' ,'nombre' => 'Traspaso a código de bonificación ']);
        TipoAjuste::create(['codigo' => 'SALERP','tipo' => 'SALIDA' ,'nombre' => 'Error en producción']);
    }
}
