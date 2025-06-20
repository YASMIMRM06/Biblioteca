<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = ['livro_id', 'usuario_id', 'data_reserva', 'status'];

    protected $casts = [
        'data_reserva' => 'datetime',
    ];

    public function livro()
    {
        return $this->belongsTo(Livro::class, 'livro_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}