<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\EditoraController;
use App\Http\Controllers\EmprestimoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\RelatorioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // User Profile routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin-specific routes (Librarian)
    Route::middleware(['can:isLibrarian'])->group(function () {
        // User Management (CRUD) - accessible by Librarian
        Route::resource('users', UserController::class);

        // Book Management (CRUD)
        Route::resource('books', LivroController::class);

        // Publisher Management (CRUD)
        Route::resource('publishers', EditoraController::class);

        // Loan Management
        Route::get('/loans', [EmprestimoController::class, 'index'])->name('loans.index');
        Route::get('/loans/create', [EmprestimoController::class, 'create'])->name('loans.create');
        Route::post('/loans', [EmprestimoController::class, 'store'])->name('loans.store');
        Route::get('/loans/{loan}/return', [EmprestimoController::class, 'showReturnForm'])->name('returns.create');
        Route::put('/loans/{loan}/return', [EmprestimoController::class, 'processReturn'])->name('returns.store');
        Route::put('/loans/{loan}/renew', [EmprestimoController::class, 'renew'])->name('loans.renew'); // Librarian can renew
        Route::get('/loans/fines', [EmprestimoController::class, 'fines'])->name('fines.index');


        // Reservation Management
        Route::get('/reservations/manage', [ReservaController::class, 'index'])->name('reservations.index'); // Librarian view all
        Route::get('/reservations/manage/{reservation}/edit', [ReservaController::class, 'edit'])->name('reservations.edit');
        Route::put('/reservations/manage/{reservation}', [ReservaController::class, 'update'])->name('reservations.update');
        Route::delete('/reservations/manage/{reservation}', [ReservaController::class, 'destroy'])->name('reservations.destroy');

        // Reports
        Route::get('/reports', [RelatorioController::class, 'index'])->name('reports.index');
        Route::get('/reports/overdue-loans', [RelatorioController::class, 'overdueLoansReport'])->name('reports.overdue_loans');
        Route::get('/reports/most-borrowed-books', [RelatorioController::class, 'mostBorrowedBooksReport'])->name('reports.most_borrowed_books');
    });

    // User-specific routes
    Route::middleware(['can:isUser'])->group(function () {
        Route::get('/my-loans', [EmprestimoController::class, 'myLoans'])->name('loans.my');
        Route::put('/my-loans/{loan}/renew', [EmprestimoController::class, 'renew'])->name('loans.user_renew'); // User can renew their own loan

        Route::get('/my-reservations', [ReservaController::class, 'myReservations'])->name('reservations.my');
        Route::get('/reservations/new', [ReservaController::class, 'create'])->name('reservations.create');
        Route::post('/reservations', [ReservaController::class, 'store'])->name('reservations.store');

        // Book Search (common for both, but for specific user path)
        Route::get('/books/search', [LivroController::class, 'index'])->name('books.search'); // Uses index with search parameter
    });

    // Common routes (accessible by both librarians and regular users)
    Route::get('/books', [LivroController::class, 'index'])->name('books.index'); // General book listing (can also be search)
    Route::get('/books/{book}', [LivroController::class, 'show'])->name('books.show');
    Route::get('/publishers', [EditoraController::class, 'index'])->name('publishers.index'); // Can view all publishers
    Route::get('/publishers/{publisher}', [EditoraController::class, 'show'])->name('publishers.show');
});

require __DIR__.'/auth.php';