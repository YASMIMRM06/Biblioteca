<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emprestimo extends Model
{
    use HasFactory;

    protected $fillable = ['livro_id', 'usuario_id', 'data_emprestimo', 'data_devolucao', 'status'];

    protected $casts = [
        'data_emprestimo' => 'date',
        'data_devolucao' => 'date',
    ];

    public function livro()
    {
        return $this->belongsTo(Livro::class, 'livro_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Calculate the fine for the loan if overdue.
     * Example: R$2.50 per day after 7 days loan period.
     */
    public function calcularMulta(): float
    {
        if ($this->status === 'ativo' && $this->data_devolucao && $this->data_devolucao < now()->toDateString()) {
            $expected_return_date = $this->data_emprestimo->addDays(7); // Assuming 7 days loan period
            if (now()->toDate() > $expected_return_date) {
                $days_overdue = now()->toDate()->diffInDays($expected_return_date);
                return $days_overdue * 2.50; // Example fine: R$2.50 per day
            }
        }
        return 0.0;
    }
}