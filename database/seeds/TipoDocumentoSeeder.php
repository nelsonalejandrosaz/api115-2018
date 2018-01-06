<?php

use App\TipoDocumento;
use Illuminate\Database\Seeder;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoDocumento::create([
            'codigo' => 'FAC',
            'nombre' => 'Factura',
        ]);
        TipoDocumento::create([
            'codigo' => 'CCF',
            'nombre' => 'Crédito Fiscal',
        ]);
    }
}
