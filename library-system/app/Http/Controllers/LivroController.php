<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Editora; // Importar o modelo Editora para o formulário
use Illuminate\Http\Request;

class LivroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Recupera todos os livros com seus relacionamentos de editora carregados
        $livros = Livro::with('editora')->paginate(10);
        return view('livros.index', compact('livros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Recupera todas as editoras para preencher o campo select no formulário
        $editoras = Editora::all();
        return view('livros.create', compact('editoras'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'isbn' => 'required|string|max:17|unique:livros,isbn', // ISBN deve ser único
            'ano_publicacao' => 'required|integer|min:1000|max:' . date('Y'), // Ano válido
            'qtd_exemplares' => 'required|integer|min:0',
            'status' => 'required|string|in:disponivel,emprestado,reservado,inativo', // Enum de status
            'editora_id' => 'required|exists:editoras,id', // Deve existir na tabela editoras
        ]);

        Livro::create($request->all());

        return redirect()->route('livros.index')->with('success', 'Livro criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Livro $livro)
    {
        // Carrega o relacionamento com a editora
        $livro->load('editora');
        return view('livros.show', compact('livro'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Livro $livro)
    {
        $editoras = Editora::all();
        return view('livros.edit', compact('livro', 'editoras'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Livro $livro)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'isbn' => 'required|string|max:17|unique:livros,isbn,' . $livro->id, // ISBN único, exceto o atual
            'ano_publicacao' => 'required|integer|min:1000|max:' . date('Y'),
            'qtd_exemplares' => 'required|integer|min:0',
            'status' => 'required|string|in:disponivel,emprestado,reservado,inativo',
            'editora_id' => 'required|exists:editoras,id',
        ]);

        $livro->update($request->all());

        return redirect()->route('livros.index')->with('success', 'Livro atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Livro $livro)
    {
        // Verifica se existem empréstimos ou reservas associados a este livro
        if ($livro->emprestimos()->exists() || $livro->reservas()->exists()) {
            return redirect()->route('livros.index')->with('error', 'Não é possível excluir o livro, pois há empréstimos ou reservas associados a ele.');
        }

        $livro->delete();
        return redirect()->route('livros.index')->with('success', 'Livro excluído com sucesso!');
    }
}