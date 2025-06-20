<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use App\Models\Livro; // Para selecionar livros
use App\Models\User;  // Para selecionar usuários (usuários do Laravel)
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Para validação avançada

class EmprestimoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Carrega empréstimos com os relacionamentos de livro e usuário
        $emprestimos = Emprestimo::with(['livro', 'user'])->paginate(10);
        return view('emprestimos.index', compact('emprestimos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $livros = Livro::where('status', 'disponivel')->get(); // Apenas livros disponíveis para empréstimo
        $users = User::all(); // Todos os usuários que podem pegar livros
        return view('emprestimos.create', compact('livros', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'livro_id' => [
                'required',
                'exists:livros,id',
                // Verifica se o livro está disponível no momento do empréstimo
                Rule::exists('livros', 'id')->where(function ($query) {
                    $query->where('status', 'disponivel');
                }),
            ],
            'user_id' => 'required|exists:users,id',
            'data_emprestimo' => 'required|date',
            'data_devolucao_prevista' => 'required|date|after_or_equal:data_emprestimo',
            'status' => 'required|string|in:pendente,devolvido,atrasado',
        ]);

        // Cria o empréstimo
        $emprestimo = Emprestimo::create($request->all());

        // Atualiza o status do livro para 'emprestado'
        $livro = Livro::find($request->livro_id);
        $livro->status = 'emprestado';
        $livro->save();

        return redirect()->route('emprestimos.index')->with('success', 'Empréstimo registrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Emprestimo $emprestimo)
    {
        $emprestimo->load(['livro', 'user']);
        return view('emprestimos.show', compact('emprestimo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Emprestimo $emprestimo)
    {
        $livros = Livro::all(); // Todos os livros (pode incluir emprestado/reservado aqui para edição)
        $users = User::all();
        return view('emprestimos.edit', compact('emprestimo', 'livros', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Emprestimo $emprestimo)
    {
        $request->validate([
            'livro_id' => 'required|exists:livros,id',
            'user_id' => 'required|exists:users,id',
            'data_emprestimo' => 'required|date',
            'data_devolucao_prevista' => 'required|date|after_or_equal:data_emprestimo',
            'data_devolucao_real' => 'nullable|date|after_or_equal:data_emprestimo',
            'status' => 'required|string|in:pendente,devolvido,atrasado',
        ]);

        // Lógica para mudar o status do livro ao devolver
        $oldStatus = $emprestimo->status;
        $newStatus = $request->input('status');

        $emprestimo->update($request->all());

        if ($oldStatus !== 'devolvido' && $newStatus === 'devolvido') {
            // Se o empréstimo foi marcado como devolvido, o livro deve voltar a ser 'disponivel'
            $livro = Livro::find($emprestimo->livro_id);
            if ($livro) {
                $livro->status = 'disponivel';
                $livro->save();
            }
        } elseif ($oldStatus === 'devolvido' && $newStatus !== 'devolvido') {
            // Se o status foi revertido de 'devolvido' para outro (pode ser um erro ou correção)
            $livro = Livro::find($emprestimo->livro_id);
            if ($livro) {
                $livro->status = 'emprestado'; // Ou outro status apropriado
                $livro->save();
            }
        }

        return redirect()->route('emprestimos.index')->with('success', 'Empréstimo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Emprestimo $emprestimo)
    {
        // Lógica para liberar o livro se o empréstimo for excluído
        $livro = Livro::find($emprestimo->livro_id);
        if ($livro && $emprestimo->status !== 'devolvido') {
             // Apenas muda o status se não foi devolvido ainda
             $livro->status = 'disponivel';
             $livro->save();
        }

        $emprestimo->delete();
        return redirect()->route('emprestimos.index')->with('success', 'Empréstimo excluído com sucesso!');
    }
}