<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    // Método para alugar livro
    public function reserve($book_id, Request $request)
    {
        DB::beginTransaction();
        
        try {
            $book = Book::findOrFail($book_id);
            $user = auth()->user();

            // Verificação de disponibilidade real
            if (!$book->disponibilidade_real) {
                throw new \Exception('O livro não está disponível para empréstimo no momento.');
            }

            // Verificar se usuário está bloqueado
            if ($user->block == 1) {
                throw new \Exception('Sua conta está bloqueada. Não é possível realizar empréstimos.');
            }

            // Verificar se já tem este livro emprestado
            $existingLoan = Loan::where('user_id', $user->id)
                ->where('book_id', $book_id)
                ->where('status', '!=', 'devolvido')
                ->exists();
                
            if ($existingLoan) {
                throw new \Exception('Você já possui um exemplar deste livro emprestado.');
            }

            // Criar o empréstimo
            $loan = new Loan();
            $loan->user_id = $user->id;
            $loan->book_id = $book_id;
            $loan->devolution_date = Carbon::now()->addDays(7);
            $loan->status = 'em andamento';
            $loan->save();

            // Atualizar estoque do livro
            $book->num_exemplares -= 1;
            $book->atualizarDisponibilidade();
            $book->save();

            DB::commit();

            return redirect()->back()->with('success', 
                'Livro alugado com sucesso! Data de devolução: ' . 
                $loan->devolution_date->format('d/m/Y'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Método para devolver livro
    public function devolver($loan_id)
    {
        DB::beginTransaction();
        
        try {
            $loan = Loan::findOrFail($loan_id);
            $book = $loan->book;

            // Verificar se o empréstimo já foi devolvido
            if ($loan->status == 'devolvido') {
                throw new \Exception('Este livro já foi devolvido.');
            }

            // Atualizar status do empréstimo
            $loan->status = 'devolvido';
            $loan->save();

            // Atualizar estoque do livro
            $book->num_exemplares += 1;
            $book->atualizarDisponibilidade();
            $book->save();

            DB::commit();

            return redirect()->back()->with('success', 'Livro devolvido com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}