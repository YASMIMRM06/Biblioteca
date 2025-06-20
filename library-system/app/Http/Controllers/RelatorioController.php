<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use App\Models\Livro;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RelatorioController extends Controller
{
    public function index()
    {
        $this->authorize('manage-loans', Emprestimo::class); // Only librarians can generate reports
        return view('reports.index');
    }

    public function overdueLoansReport()
    {
        $this->authorize('manage-loans', Emprestimo::class);
        $overdueLoans = Emprestimo::with(['livro', 'usuario'])
                                    ->where('status', 'ativo')
                                    ->whereDate('data_devolucao', '<', Carbon::now()->toDateString())
                                    ->get();
        return view('reports.overdue_loans', compact('overdueLoans'));
    }

    public function mostBorrowedBooksReport()
    {
        $this->authorize('manage-loans', Emprestimo::class);
        $mostBorrowed = Livro::withCount('emprestimos')
                                ->orderByDesc('emprestimos_count')
                                ->take(10)
                                ->get();
        return view('reports.most_borrowed_books', compact('mostBorrowed'));
    }

    // You can add more report methods here
}