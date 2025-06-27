<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo', 
        'genero', 
        'autor', 
        'sinopse', 
        'avaliacao',
        'ano_lancamento', 
        'num_exemplares', 
        'num_paginas',
        'url_img', 
        'disponibilidade'
    ];

    public $timestamps = false;

    // Relacionamentos existentes (mantidos)
    public function reservations()
    {
        return $this->hasMany('App\Models\Reservation');
    }

    // Novo método para verificar disponibilidade real
    public function getDisponibilidadeRealAttribute()
    {
        return $this->num_exemplares > 0 && $this->disponibilidade == 1;
    }

    // Método para atualizar status de disponibilidade
    public function atualizarDisponibilidade()
    {
        $this->disponibilidade = ($this->num_exemplares > 0) ? 1 : 0;
        $this->save();
    }
}