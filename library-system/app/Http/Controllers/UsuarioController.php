<?php

namespace App\Http\Controllers;

use App\Models\User; // Usamos o modelo User padrão do Laravel, que você mapeou como Usuario
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Carrega todos os usuários, pode adicionar paginação
        $usuarios = User::paginate(10);
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Definir os tipos de usuário disponíveis (ex: 'administrador', 'bibliotecario', 'cliente')
        $tiposUsuario = ['administrador', 'bibliotecario', 'cliente'];
        return view('usuarios.create', compact('tiposUsuario'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' requer campo 'password_confirmation'
            'tipo' => 'required|string|in:administrador,bibliotecario,cliente', // Baseado no seu diagrama
            'status' => 'required|string|in:ativo,inativo,bloqueado', // Exemplo de status de usuário
            'telefone' => 'nullable|string|max:20', // Do seu DER
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Criptografar a senha
            'tipo' => $request->tipo,
            'status' => $request->status,
            'telefone' => $request->telefone,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario) // Usando o Type Hinting com User
    {
        return view('usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario)
    {
        $tiposUsuario = ['administrador', 'bibliotecario', 'cliente'];
        return view('usuarios.edit', compact('usuario', 'tiposUsuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // O email deve ser único, exceto para o usuário atual
            'email' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:8|confirmed', // Senha é opcional na edição
            'tipo' => 'required|string|in:administrador,bibliotecario,cliente',
            'status' => 'required|string|in:ativo,inativo,bloqueado',
            'telefone' => 'nullable|string|max:20',
        ]);

        $data = $request->except('password', 'password_confirmation'); // Exclui a senha se não for alterada

        if ($request->filled('password')) { // Se uma nova senha foi fornecida
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        // Verificar se o usuário tem empréstimos ou reservas ativas/pendentes
        if ($usuario->emprestimos()->whereIn('status', ['pendente', 'atrasado'])->exists() ||
            $usuario->reservas()->where('status', 'pendente')->exists()) {
            return redirect()->route('usuarios.index')->with('error', 'Não é possível excluir o usuário, pois há empréstimos ou reservas pendentes.');
        }

        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuário excluído com sucesso!');
    }
}