<?php

use App\Departamento;
use Illuminate\Database\Seeder;

class DepartamentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Departamento::create(['nombre' => 'Ahuachapán', 'isocode' => 'SV-AH', 'zonesv_id' => '1']);
        Departamento::create(['nombre' => 'Santa Ana', 'isocode' => 'SV-SA', 'zonesv_id' => '1']);
        Departamento::create(['nombre' => 'Sonsonate', 'isocode' => 'SV-SO', 'zonesv_id' => '1']);
        Departamento::create(['nombre' => 'La Libertad', 'isocode' => 'SV-LI', 'zonesv_id' => '2']);
        Departamento::create(['nombre' => 'Chalatenango', 'isocode' => 'SV-CH', 'zonesv_id' => '2']);
        Departamento::create(['nombre' => 'San Salvador', 'isocode' => 'SV-SS', 'zonesv_id' => '2']);
        Departamento::create(['nombre' => 'Cuscatlán', 'isocode' => 'SV-CU', 'zonesv_id' => '3']);
        Departamento::create(['nombre' => 'La Paz', 'isocode' => 'SV-PA', 'zonesv_id' => '3']);
        Departamento::create(['nombre' => 'Cabañas', 'isocode' => 'SV-CA', 'zonesv_id' => '3']);
        Departamento::create(['nombre' => 'San Vicente', 'isocode' => 'SV-SV', 'zonesv_id' => '3']);
        Departamento::create(['nombre' => 'Usulután', 'isocode' => 'SV-US', 'zonesv_id' => '4']);
        Departamento::create(['nombre' => 'Morazán', 'isocode' => 'SV-MO', 'zonesv_id' => '4']);
        Departamento::create(['nombre' => 'San Miguel', 'isocode' => 'SV-SM', 'zonesv_id' => '4']);
        Departamento::create(['nombre' => 'La Unión', 'isocode' => 'SV-UN', 'zonesv_id' => '4']);
    }
}
