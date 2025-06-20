<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Perfil extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'telefone',
        'tipo',
    ];

    // Relacionamento 1-1 com User (inverso de User->perfil())
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}