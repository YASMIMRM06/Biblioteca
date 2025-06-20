<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LivroApiController; // Exemplo para API

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Exemplo de rota API para Livros
Route::apiResource('livros', LivroApiController::class)->middleware('auth:sanctum');
// Adicione outras apiResources conforme seus modelos