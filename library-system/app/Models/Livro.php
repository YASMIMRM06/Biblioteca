<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- ESTA LINHA É FUNDAMENTAL!
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Livro extends Model
{
    use HasFactory; // <--- E ESTA AQUI TAMBÉM!

    protected $fillable = [
        'titulo',
        'autor',
        'isbn',
        'ano_publicacao',
        'qtd_exemplares',
        'status',
        'editora_id',
    ];

    // Seus relacionamentos...
    public function editora(): BelongsTo
    {
        return $this->belongsTo(Editora::class);
    }

    public function emprestimos(): HasMany
    {
        return $this->hasMany(Emprestimo::class);
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }
}