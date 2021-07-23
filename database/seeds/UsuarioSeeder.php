<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Sergio',
            'email' => 'correo@correo.com',
            'password' => Hash::make('12345678'),
            'url' => 'http://www.fb-sergio.com',
        ]);
        $user2 = User::create([
            'name' => 'Dul',
            'email' => 'dul@correo.com',
            'password' => Hash::make('12345678'),
            'url' => 'http://www.fb-dul.com',
        ]);
    }
}
