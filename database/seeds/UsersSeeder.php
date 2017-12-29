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
        User::create([
            'nombre' => 'Nelson Alejandro',
            'apellido' => 'Saz',
            'email' => 'nelsonalejandrosaz@gmail.com',
            'username' => 'nelsonalejandrosaz',
            'password' => bcrypt('12345'),
            'telefono' => '7789-2352',
            'rol_id' => '1',
        ]);
        User::create([
            'nombre' => 'Administrador',
            'apellido' => 'Administrador',
            'email' => 'administrador@lgl.com',
            'username' => 'administrador1',
            'password' => bcrypt('12345'),
            'telefono' => '7777-7777',
            'rol_id' => '1',
        ]);
        User::create([
            'nombre' => 'Bodega',
            'apellido' => 'Bodega',
            'email' => 'bodega@lgl.com',
            'username' => 'bodega1',
            'password' => bcrypt('12345'),
            'telefono' => '7777-7777',
            'rol_id' => '2',
        ]);
        User::create([
            'nombre' => 'Verdedor',
            'apellido' => 'Verdedor',
            'email' => 'verdedor@lgl.com',
            'username' => 'verdedor1',
            'password' => bcrypt('12345'),
            'telefono' => '7777-7777',
            'rol_id' => '3',
        ]);
    }
}
