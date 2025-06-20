<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    protected $fillable = ['usuario_id', 'telefone'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}