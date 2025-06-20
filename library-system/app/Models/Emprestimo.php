<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- TEM QUE ESTAR AQUI!
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Emprestimo extends Model
{
    use HasFactory; // <--- TEM QUE ESTAR AQUI!

    protected $fillable = [
        'livro_id',
        'user_id',
        'data_emprestimo',
        'data_devolucao_prevista',
        'data_devolucao_real',
        'status',
    ];

    protected $casts = [
        'data_emprestimo' => 'datetime',
        'data_devolucao_prevista' => 'datetime',
        'data_devolucao_real' => 'datetime',
    ];

    public function livro(): BelongsTo
    {
        return $this->belongsTo(Livro::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}