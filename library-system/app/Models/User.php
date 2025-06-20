<?php

namespace App\Models;

// Remove MustVerifyEmail if not using email verification
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable // implements MustVerifyEmail // Uncomment if using email verification
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tipo', // Added 'tipo' to fillable
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function perfil()
    {
        return $this->hasOne(Perfil::class, 'usuario_id');
    }

    public function emprestimos()
    {
        return $this->hasMany(Emprestimo::class, 'usuario_id');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'usuario_id');
    }

    public function isLibrarian()
    {
        return $this->tipo === 'bibliotecario';
    }

    public function isUser()
    {
        return $this->tipo === 'usuario';
    }
}