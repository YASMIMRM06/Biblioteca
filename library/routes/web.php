<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\LoansController;
use App\Http\Controllers\AdminController;

// Public routes
Route::get('/', [BookController::class, 'welcome'])->name('welcome');

// Authenticated routes (regular users and admin)
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [BookController::class, 'index'])->name('home');
    Route::get('/book/{id}', [BookController::class, 'show'])->name('show');
    
    // Reservation/loan routes
    Route::post('/book/{id}/reserve', [ReservationController::class, 'reserve'])
        ->name('reservation');
    Route::post('/loan/{id}/devolver', [ReservationController::class, 'devolver'])
        ->name('devolver.livro');
});

// Admin-only routes
Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Book CRUD
    Route::get('/create', [BookController::class, 'create'])->name('create');
    Route::post('/store', [BookController::class, 'store'])->name('save');
    Route::delete('/book/{id}/delete', [BookController::class, 'destroy'])->name('destroy');
    Route::get('/book/edit/{id}', [BookController::class, 'edit'])->name('edit.book');
    Route::put('/book/update/{id}', [BookController::class, 'update'])->name('update');
    
    // Loans panel
    Route::get('/loans', [LoansController::class, 'panel'])->name('loans.panel');
    Route::put('/loans/check/{id}', [LoansController::class, 'check'])->name('requests.check');
    
    // Reservations
    Route::get('/reserve/requests', [ReservationController::class, 'requests'])
        ->name('requests.reserves');
    Route::post('/reserve/requests/validate/{id}', [ReservationController::class, 'validateReserve'])
        ->name('requests.validate');
});

// Authentication routes
Auth::routes();