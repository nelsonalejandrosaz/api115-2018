<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Proveedor::class, function () {
    $faker = \Faker\Factory::create('es_ES');
    return [
        'nombre' => $faker->company,
        'telefono1' => $faker->phoneNumber,
        'telefono2' => $faker->phoneNumber,
        'direccion' => $faker->address,
        'nombreContacto' => $faker->name,
    ];
});

$factory->define(App\Cliente::class, function () {
    $faker = \Faker\Factory::create('es_ES');
    return [
        'nombre' => $faker->company,
        'telefono1' => $faker->phoneNumber,
        'telefono2' => $faker->phoneNumber,
        'direccion' => $faker->address,
        'nombreContacto' => $faker->name,
    ];
});

$factory->define(App\Producto::class, function () {
    $faker = \Faker\Factory::create('es_ES');
//    $nombre = 'Producto ' . $faker->unique()->word;
    return [
        'tipo_producto_id' => $faker->numberBetween($min = 1, $max = 3),
        'categoria_id' => $faker->numberBetween($min = 1, $max = 4),
        'unidad_medida_id' => $faker->numberBetween($min = 1, $max = 8),
        'nombre' => $faker->numerify('Producto ###'),
        'existenciaMin' => $faker->randomElement($array = array (50,100,150)),
        'existenciaMax' => $faker->randomElement($array = array (500,1000,1500)),
    ];
});
