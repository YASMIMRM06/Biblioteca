<?php

namespace Database\Factories;

use App\Models\Emprestimo; // Importe o modelo Emprestimo
use App\Models\Livro;     // Importe o modelo Livro para o relacionamento
use App\Models\User;      // Importe o modelo User para o relacionamento
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Emprestimo>
 */
class EmprestimoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Emprestimo::class; // Aponte para o modelo Emprestimo

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Gera datas lógicas para empréstimo e devolução
        $dataEmprestimo = $this->faker->dateTimeBetween('-1 month', 'now');
        $dataDevolucaoPrevista = (clone $dataEmprestimo)->modify('+14 days'); // 14 dias para devolver
        $dataDevolucaoReal = $this->faker->boolean(70) ? // 70% de chance de ter devolvido
                            $this->faker->dateTimeBetween($dataEmprestimo, $dataDevolucaoPrevista) : // Devolveu no prazo
                            ( $this->faker->boolean(30) ? $this->faker->dateTimeBetween($dataDevolucaoPrevista, '+1 month') : null); // Devolveu atrasado ou ainda não devolveu

        $status = 'pendente';
        if ($dataDevolucaoReal !== null) {
            $status = ($dataDevolucaoReal > $dataDevolucaoPrevista) ? 'atrasado' : 'devolvido';
        } elseif ($dataEmprestimo < now()->subDays(14)) { // Se o empréstimo já passou do prazo
            $status = 'atrasado';
        }


        return [
            'livro_id' => Livro::factory(), // Cria um Livro se não existir
            'user_id' => User::factory(),    // Cria um User se não existir (o User padrão do Laravel pode ser usado ou criar um novo)
            'data_emprestimo' => $dataEmprestimo->format('Y-m-d H:i:s'),
            'data_devolucao_prevista' => $dataDevolucaoPrevista->format('Y-m-d H:i:s'),
            'data_devolucao_real' => $dataDevolucaoReal ? $dataDevolucaoReal->format('Y-m-d H:i:s') : null,
            'status' => $status,
        ];
    }
}