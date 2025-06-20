<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Models\User; // Para selecionar usuários associados a perfis
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Recupera todos os perfis com seus usuários associados
        $perfis = Perfil::with('user')->paginate(10);
        return view('perfis.index', compact('perfis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Pega usuários que ainda não têm um perfil (relacionamento 1-1)
        $usersWithoutProfile = User::doesntHave('perfil')->get();
        return view('perfis.create', compact('usersWithoutProfile'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:perfis,user_id', // Um perfil por usuário
            'telefone' => 'nullable|string|max:20',
            // Adicione outras validações para campos do perfil
        ]);

        Perfil::create($request->all());

        return redirect()->route('perfis.index')->with('success', 'Perfil criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Perfil $perfil)
    {
        $perfil->load('user'); // Carrega o usuário associado
        return view('perfis.show', compact('perfil'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perfil $perfil)
    {
        // Para editar, não precisamos listar todos os usuários sem perfil, pois o usuário já está associado
        return view('perfis.edit', compact('perfil'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perfil $perfil)
    {
        $request->validate([
            'telefone' => 'nullable|string|max:20',
            // Adicione outras validações para campos do perfil
        ]);

        $perfil->update($request->all());

        return redirect()->route('perfis.index')->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perfil $perfil)
    {
        $perfil->delete();
        return redirect()->route('perfis.index')->with('success', 'Perfil excluído com sucesso!');
    }
}