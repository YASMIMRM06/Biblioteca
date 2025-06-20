<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'livro_id',
        'user_id',
        'data_reserva',
        'data_expiracao',
        'status',
    ];

    protected $casts = [
        'data_reserva' => 'datetime',
        'data_expiracao' => 'datetime',
    ];

    // Relacionamento N-1 com Livro
    public function livro(): BelongsTo
    {
        return $this->belongsTo(Livro::class);
    }

    // Relacionamento N-1 com User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}