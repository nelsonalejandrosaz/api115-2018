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
        $this->call(CategoriasSeeder::class);
        $this->call(TipoProductosSeeder::class);
        $this->call(UnidadMedidasSeeder::class);
    }
}
