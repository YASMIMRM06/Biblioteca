<?php

namespace Database\Seeders;

use App\Models\Emprestimo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmprestimoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Emprestimo::factory()->count(30)->create();
    }
}