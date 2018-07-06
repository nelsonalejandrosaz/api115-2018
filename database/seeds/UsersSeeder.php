<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        User::create([
//            'nombre' => 'Nelson Alejandro',
//            'apellido' => 'Saz',
//            'email' => 'nelsonalejandrosaz@gmail.com',
//            'username' => 'nelsonalejandrosaz',
//            'password' => bcrypt('12345'),
//            'telefono' => '7789-2352',
//            'ruta_imagen' => 'man.png',
//            'rol_id' => '1',
//        ]);
        User::create([
            'nombre' => 'Administrador',
            'apellido' => 'Administrador',
            'email' => 'administrador@lgl.com',
            'username' => 'administrador1',
            'password' => bcrypt('12345'),
            'telefono' => '7777-7777',
            'ruta_imagen' => 'man.png',
            'rol_id' => '1',
        ]);
        User::create([
            'nombre' => 'Luis Gerardo',
            'apellido' => 'Martinez',
            'email' => 'luismartinez@lgl.com',
            'username' => 'luismartinez',
            'password' => bcrypt('12345'),
            'telefono' => '7777-7777',
            'ruta_imagen' => 'man.png',
            'rol_id' => '1',
        ]);
        User::create([
            'nombre' => 'Rafael',
            'apellido' => 'Apellido',
            'email' => 'rafael@lgl.com',
            'username' => 'administrador1',
            'password' => bcrypt('12345'),
            'telefono' => '7777-7777',
            'ruta_imagen' => 'man.png',
            'rol_id' => '1',
        ]);
        User::create([
            'nombre' => 'Manuel',
            'apellido' => 'Luna',
            'email' => 'manuelluna@lgl.com',
            'username' => 'manuelluna',
            'password' => bcrypt('12345'),
            'telefono' => '7777-7777',
            'ruta_imagen' => 'man.png',
            'rol_id' => '3',
        ]);
        User::create([
            'nombre' => 'Mauricio',
            'apellido' => 'Díaz',
            'email' => 'mauriciodiaz@lgl.com',
            'username' => 'mauriciodiaz',
            'password' => bcrypt('12345'),
            'telefono' => '7777-7777',
            'ruta_imagen' => 'man.png',
            'rol_id' => '3',
        ]);
        User::create([
            'nombre' => 'Walter',
            'apellido' => 'Corona',
            'email' => 'waltercorona@lgl.com',
            'username' => 'waltercorona',
            'password' => bcrypt('12345'),
            'telefono' => '7777-7777',
            'ruta_imagen' => 'man.png',
            'rol_id' => '3',
        ]);
        User::create([
            'nombre' => 'José',
            'apellido' => 'Díaz',
            'email' => 'josediaz@lgl.com',
            'username' => 'josediaz',
            'password' => bcrypt('12345'),
            'telefono' => '7777-7777',
            'ruta_imagen' => 'man.png',
            'rol_id' => '3',
        ]);
        User::create([
            'nombre' => 'Flor Azucena',
            'apellido' => 'Méndez de Quintanilla',
            'email' => 'flormendez@lgl.com',
            'username' => 'flormendez',
            'password' => bcrypt('12345'),
            'telefono' => '7777-7777',
            'ruta_imagen' => 'girl.png',
            'rol_id' => '2',
        ]);
        User::create([
            'nombre' => 'Jessica Samanta',
            'apellido' => 'Hernández Urias',
            'email' => 'samantahermandez@lgl.com',
            'username' => 'samantahermandez',
            'password' => bcrypt('12345'),
            'telefono' => '7777-7777',
            'ruta_imagen' => 'girl.png',
            'rol_id' => '2',
        ]);
        User::create([
            'nombre' => 'Verdedora',
            'apellido' => 'Verdedora',
            'email' => 'verdedora@lgl.com',
            'username' => 'verdedora1',
            'password' => bcrypt('12345'),
            'telefono' => '7777-7777',
            'ruta_imagen' => 'girl.png',
            'rol_id' => '2',
        ]);
    }
}
