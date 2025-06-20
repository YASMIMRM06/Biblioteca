<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Livro;
use App\Policies\LivroPolicy;
use App\Models\Emprestimo;
use App\Policies\EmprestimoPolicy;
use App\Models\Reserva;
use App\Policies\ReservaPolicy;
use App\Models\Editora;
use App\Policies\EditoraPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Livro::class => LivroPolicy::class,
        Emprestimo::class => EmprestimoPolicy::class,
        Reserva::class => ReservaPolicy::class,
        Editora::class => EditoraPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}