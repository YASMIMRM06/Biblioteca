<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Acesso não autorizado');
        }

        // Dados para os gráficos
        $popularBooks = Book::withCount('loans')
            ->orderBy('loans_count', 'desc')
            ->take(5)
            ->get();

        $loansLastMonths = Loan::selectRaw('count(*) as count, MONTH(created_at) as month')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->get();

        $activeLoans = Loan::with(['user', 'book'])
            ->where('status', '!=', 'devolvido')
            ->orderBy('devolution_date')
            ->get();

        return view('admin.dashboard', compact('popularBooks', 'loansLastMonths', 'activeLoans'));
    }
}