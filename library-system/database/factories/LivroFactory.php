<?php

namespace Database\Factories;

use App\Models\Editora; // <--- Certifique-se que esta linha está aqui!
use App\Models\Livro;   // <--- Certifique-se que esta linha está aqui!
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Livro>
 */
class LivroFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Livro::class; // <--- GARANTA que esta linha está aqui!

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(3),
            'autor' => $this->faker->name(),
            'isbn' => $this->faker->unique()->isbn13(),
            'ano_publicacao' => $this->faker->year(),
            'qtd_exemplares' => $this->faker->numberBetween(1, 10),
            'status' => $this->faker->randomElement(['disponivel', 'emprestado', 'reservado']),
            'editora_id' => Editora::factory(), // Cria uma editora se não houver
        ];
    }
}