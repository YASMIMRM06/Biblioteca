<?php

namespace App\Http\Controllers;

use App\Models\Editora;
use Illuminate\Http\Request;

class EditoraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Recupera todas as editoras do banco de dados, com paginação para melhor performance
        $editoras = Editora::paginate(10);
        // Retorna a view 'editoras.index' e passa as editoras para ela
        return view('editoras.index', compact('editoras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Retorna a view 'editoras.create' que contém o formulário para criar uma nova editora
        return view('editoras.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Valida os dados recebidos do formulário
        $request->validate([
            'nome' => 'required|string|max:255',
            'cnpj' => 'required|string|max:18|unique:editoras,cnpj', // CNPJ deve ser único na tabela 'editoras'
        ]);

        // Cria uma nova editora no banco de dados com os dados validados
        Editora::create($request->all());

        // Redireciona de volta para a lista de editoras com uma mensagem de sucesso
        return redirect()->route('editoras.index')->with('success', 'Editora criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Editora $editora)
    {
        // Retorna a view 'editoras.show' e passa a editora específica para ela
        return view('editoras.show', compact('editora'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Editora $editora)
    {
        // Retorna a view 'editoras.edit' e passa a editora para ser editada
        return view('editoras.edit', compact('editora'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Editora $editora)
    {
        // Valida os dados recebidos para atualização
        $request->validate([
            'nome' => 'required|string|max:255',
            // CNPJ deve ser único, exceto para a própria editora que está sendo atualizada
            'cnpj' => 'required|string|max:18|unique:editoras,cnpj,' . $editora->id,
        ]);

        // Atualiza a editora no banco de dados com os dados validados
        $editora->update($request->all());

        // Redireciona de volta para a lista de editoras com uma mensagem de sucesso
        return redirect()->route('editoras.index')->with('success', 'Editora atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Editora $editora)
    {
        // Verifica se existem livros associados a esta editora antes de deletar
        // Se a sua base de dados tiver restrições de chave estrangeira ON DELETE RESTRICT,
        // isso vai falhar, e é bom capturar a exceção ou verificar antes.
        if ($editora->livros()->exists()) {
            return redirect()->route('editoras.index')->with('error', 'Não é possível excluir a editora, pois há livros associados a ela.');
        }

        // Deleta a editora do banco de dados
        $editora->delete();

        // Redireciona de volta para a lista de editoras com uma mensagem de sucesso
        return redirect()->route('editoras.index')->with('success', 'Editora excluída com sucesso!');
    }
}