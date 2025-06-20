<?php

namespace Database\Factories;

use App\Models\Reserva; // Importe o modelo Reserva
use App\Models\Livro;   // Importe o modelo Livro para o relacionamento
use App\Models\User;    // Importe o modelo User para o relacionamento
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reserva>
 */
class ReservaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reserva::class; // Aponte para o modelo Reserva

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'livro_id' => Livro::factory(), // Cria um Livro se não existir
            'user_id' => User::factory(),    // Cria um User se não existir
            'data_reserva' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'status' => $this->faker->randomElement(['pendente', 'cancelada', 'concluida']),
        ];
    }
}