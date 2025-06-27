<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@biblioteca.com',
            'password' => Hash::make('senha_admin_segura'),
            'credibility' => 100,
            'block' => 0,
            'is_admin' => true
        ]);
        
        $this->command->info('Usuário admin criado com sucesso!');
        $this->command->info('Email: admin@biblioteca.com');
        $this->command->info('Senha: senha_admin_segura');
    }
}