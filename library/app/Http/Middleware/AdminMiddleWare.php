<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect('/login')->with('Error', 'Faça o login para acessar a página');
        }

        if (auth()->user()->is_admin || auth()->user()->level) {
            return $next($request);
        }

        return redirect('/')->with('Error', 'Você não tem permissão de acessar essa página');
    }
}