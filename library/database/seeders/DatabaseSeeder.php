<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create main admin
        User::firstOrCreate(
            ['email' => 'admin@biblioteca.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('senha_admin_segura'),
                'is_admin' => true,
                'level' => 1,
                'credibility' => 100,
                'block' => 0
            ]
        );
        
        // Create test user
        User::firstOrCreate(
            ['email' => 'usuario@teste.com'],
            [
                'name' => 'Usuario Teste',
                'password' => Hash::make('usuario123'),
                'is_admin' => false,
                'level' => 0,
                'credibility' => 100,
                'block' => 0
            ]
        );

        Book::factory()->count(20)->create();
    }
}