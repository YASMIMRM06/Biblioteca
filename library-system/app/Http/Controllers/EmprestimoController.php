<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use App\Models\Livro;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmprestimoController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Emprestimo::class);
        $emprestimos = Emprestimo::with(['livro', 'usuario'])->paginate(10);
        return view('loans.index', compact('emprestimos'));
    }

    // For "Registrar Empréstimo"
    public function create()
    {
        $this->authorize('create', Emprestimo::class);
        $livros = Livro::where('qtd_exemplares', '>', 0)->get();
        $usuarios = User::where('tipo', 'usuario')->get();
        return view('loans.create', compact('livros', 'usuarios'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Emprestimo::class);

        $request->validate([
            'livro_id' => 'required|exists:livros,id',
            'usuario_id' => 'required|exists:users,id',
            'data_emprestimo' => 'required|date',
        ]);

        $livro = Livro::findOrFail($request->livro_id);

        if ($livro->qtd_exemplares <= 0) {
            return redirect()->back()->with('error', 'Book is not available for loan.');
        }

        Emprestimo::create([
            'livro_id' => $request->livro_id,
            'usuario_id' => $request->usuario_id,
            'data_emprestimo' => $request->data_emprestimo,
            'data_devolucao' => Carbon::parse($request->data_emprestimo)->addDays(7)->toDateString(), // Default return in 7 days
            'status' => 'ativo',
        ]);

        $livro->decrement('qtd_exemplares'); // Decrease available copies
        if ($livro->qtd_exemplares === 0) {
            $livro->update(['status' => 'emprestado']); // Update book status
        }

        return redirect()->route('loans.index')->with('success', 'Loan registered successfully.');
    }

    // For "Registrar Devolução"
    public function showReturnForm(Emprestimo $loan)
    {
        $this->authorize('update', $loan); // Or a specific policy method for returns
        return view('returns.create', compact('loan'));
    }

    public function processReturn(Request $request, Emprestimo $loan)
    {
        $this->authorize('update', $loan); // Or a specific policy method for returns

        $request->validate([
            'data_devolucao_real' => 'required|date|after_or_equal:data_emprestimo',
        ]);

        $loan->update([
            'data_devolucao' => $request->data_devolucao_real,
            'status' => 'devolvido',
        ]);

        $livro = $loan->livro;
        $livro->increment('qtd_exemplares'); // Increase available copies
        $livro->update(['status' => 'disponivel']); // Update book status

        $multa = $loan->calcularMulta();
        if ($multa > 0) {
            return redirect()->route('loans.index')->with('warning', "Loan returned, but a fine of R$" . number_format($multa, 2) . " applies.");
        }

        return redirect()->route('loans.index')->with('success', 'Book returned successfully.');
    }

    // For "Renovar Empréstimo"
    public function renew(Emprestimo $loan)
    {
        $this->authorize('renew', $loan);

        if ($loan->status === 'ativo') {
            $loan->update([
                'data_devolucao' => Carbon::parse($loan->data_devolucao)->addDays(7)->toDateString(), // Extend for another 7 days
            ]);
            return redirect()->back()->with('success', 'Loan renewed successfully.');
        }
        return redirect()->back()->with('error', 'Loan cannot be renewed.');
    }

    // For "Visualizar Meus Empréstimos"
    public function myLoans()
    {
        $user = auth()->user();
        $emprestimos = $user->emprestimos()->with('livro')->paginate(10);
        return view('loans.my_loans', compact('emprestimos'));
    }

    // Fines management (separate views/methods might be better for full management)
    public function fines()
    {
        $this->authorize('manage-loans', Emprestimo::class); // Assumes librarian permission
        $loansWithFines = Emprestimo::where('status', 'ativo')
                                    ->whereDate('data_devolucao', '<', now())
                                    ->get()
                                    ->filter(fn ($loan) => $loan->calcularMulta() > 0);
        return view('fines.index', compact('loansWithFines'));
    }
}