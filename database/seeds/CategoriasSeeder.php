<?php

use App\Categoria;
use Illuminate\Database\Seeder;

class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Categoria::create(['codigo' => 'ACE', 'nombre' => 'Aceites', 'descripcion' => 'Base de aceite']);
        Categoria::create(['codigo' => 'ESN', 'nombre' => 'Esencias', 'descripcion' => 'Base de harina']);
        Categoria::create(['codigo' => 'ESE', 'nombre' => 'Esencias Espesantes', 'descripcion' => 'Colorantes artificiales']);
        Categoria::create(['codigo' => 'VAI', 'nombre' => 'Vainillas', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'PRV', 'nombre' => 'Preservantes', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ARA', 'nombre' => 'Aromas "A"', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ARB', 'nombre' => 'Aromas "B"', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'CL1', 'nombre' => 'Colorantes (1)', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'CL4', 'nombre' => 'Colorantes (4)', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'CL2', 'nombre' => 'Colorantes (2)', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'CL4', 'nombre' => 'Colorantes (3)', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'CLL', 'nombre' => 'Colorantes Liquidos', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'CLG', 'nombre' => 'Colorantes Gel', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'PRC', 'nombre' => 'Producto de Consumo', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'EML', 'nombre' => 'Emulsificante', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'EST', 'nombre' => 'Estabilizadores', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ARM', 'nombre' => 'Aromas', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'SBP', 'nombre' => 'Saborizantes en polvo', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ADT', 'nombre' => 'Aditivos', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ACD', 'nombre' => 'Acidulante', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ANX', 'nombre' => 'Antioxidantes', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'EDC', 'nombre' => 'Edulcorantes', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'VAR', 'nombre' => 'Varios', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'EPT', 'nombre' => 'Espesantes', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'CLP', 'nombre' => 'Colorante puro', 'descripcion' => 'Esencias de olor']);
    }
}
