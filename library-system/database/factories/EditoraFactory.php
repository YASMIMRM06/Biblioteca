<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Editora; // Certifique-se de que esta linha está presente e correta

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Editora>
 */
class EditoraFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Editora::class; // Adicione ou verifique esta linha se não existir

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->unique()->company(),
            'cnpj' => $this->faker->unique()->numerify('##############'), // CNPJ com 14 dígitos
        ];
    }
}