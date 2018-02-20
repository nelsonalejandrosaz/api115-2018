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
            'nombre' => 'Factura Consumidor Final',
        ]);
        TipoDocumento::create([
            'codigo' => 'CCF',
            'nombre' => 'Comprobante Crédito Fiscal',
        ]);
    }
}
