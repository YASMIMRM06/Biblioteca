<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livro extends Model
{
    use HasFactory;

    protected $fillable = ['editora_id', 'titulo', 'isbn', 'ano_publicacao', 'qtd_exemplares', 'autor', 'status'];

    public function editora()
    {
        return $this->belongsTo(Editora::class, 'editora_id');
    }

    public function emprestimos()
    {
        return $this->hasMany(Emprestimo::class, 'livro_id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'livro_id');
    }
}