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
        Categoria::create(['codigo' => 'ACT', 'nombre' => 'Aceites', 'descripcion' => 'Base de aceite']);
        Categoria::create(['codigo' => 'HAR', 'nombre' => 'Esencias', 'descripcion' => 'Base de harina']);
        Categoria::create(['codigo' => 'COL', 'nombre' => 'Esencias Espesantes', 'descripcion' => 'Colorantes artificiales']);
        Categoria::create(['codigo' => 'ESE1', 'nombre' => 'Vainillas', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ESE2', 'nombre' => 'Preservantes', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ESE3', 'nombre' => 'Aromas "A"', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ESE4', 'nombre' => 'Aromas "B"', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ESE5', 'nombre' => 'Colorantes (1)', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ESE6', 'nombre' => 'Colorantes (4)', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ESE7', 'nombre' => 'Colorantes (2)', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ESE8', 'nombre' => 'Colorantes (3)', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ESE9', 'nombre' => 'Colorantes Liquidos', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ESE0', 'nombre' => 'Colorantes Gel', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ES1', 'nombre' => 'Producto de Consumo', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ES2', 'nombre' => 'Emulsificante', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ES3', 'nombre' => 'Estabilizadores', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ES4', 'nombre' => 'Aromas', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ES5', 'nombre' => 'Saborizantes en polvo', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ES6', 'nombre' => 'Aditivos', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ES7', 'nombre' => 'Acidulante', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ES8', 'nombre' => 'Antioxidantes', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ES9', 'nombre' => 'Edulcorantes', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'ES0', 'nombre' => 'Varios', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'E11', 'nombre' => 'Espesantes', 'descripcion' => 'Esencias de olor']);
        Categoria::create(['codigo' => 'E12', 'nombre' => 'Colorante puro', 'descripcion' => 'Esencias de olor']);
    }
}
