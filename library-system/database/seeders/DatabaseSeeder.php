<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Cria um usuário de exemplo (gerado pelo Breeze)
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Chama outros seeders para popular suas tabelas personalizadas
        $this->call([
            EditoraSeeder::class,
            LivroSeeder::class,
            EmprestimoSeeder::class,
            ReservaSeeder::class,
            // Se precisar de usuários com perfis específicos, crie um PerfilSeeder
            // PerfilSeeder::class,
        ]);
    }
}