<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crea un usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@correo.com',
            'password' => Hash::make('12345678'), // contraseña: 12345678
        ]);
    }
}
