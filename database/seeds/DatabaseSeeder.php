<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(RolsSeeder::class);
        $this->call(TipoProductosSeeder::class);
        $this->call(UnidadMedidasSeeder::class);
        $this->call(ConversionUnidadMedidaSeeder::class);
        $this->call(TipoMovimientosSeeder::class);
        $this->call(TipoAjustesSeeder::class);
        $this->call(MunicipiosSeeder::class);
        $this->call(TipoDocumentoSeeder::class);
        $this->call(DepartamentosSeeder::class);
        $this->call(EstadosCompraSeeder::class);
        $this->call(EstadosOrdenSeeder::class);
        $this->call(EstadosVentaSeeder::class);
        $this->call(CondicionesPagoSeeder::class);

//        $this->call(CategoriasSeeder::class);
//        $this->call(ClientesSeeder::class);
//        $this->call(ProveedorSeeder::class);
//        $this->call(ProductosSeeder::class);
    }
}
