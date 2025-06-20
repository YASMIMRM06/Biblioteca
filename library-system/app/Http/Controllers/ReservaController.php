<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Livro;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservas = Reserva::with(['livro', 'user'])->paginate(10);
        return view('reservas.index', compact('reservas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $livros = Livro::all(); // Todos os livros, pois podem ser reservados mesmo se emprestados
        $users = User::all();
        return view('reservas.create', compact('livros', 'users'));
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
                // Opcional: Impedir reservar o mesmo livro várias vezes pelo mesmo usuário se não quiser isso
                Rule::unique('reservas')->where(function ($query) use ($request) {
                    return $query->where('user_id', $request->user_id)
                                 ->where('livro_id', $request->livro_id)
                                 ->whereIn('status', ['pendente']); // Apenas uma reserva pendente por livro/usuário
                }),
            ],
            'user_id' => 'required|exists:users,id',
            'data_reserva' => 'required|date',
            'status' => 'required|string|in:pendente,cancelada,concluida',
        ]);

        // Criar a reserva
        $reserva = Reserva::create($request->all());

        // Se o livro estiver 'disponivel' e for reservado, seu status pode mudar para 'reservado'
        $livro = Livro::find($request->livro_id);
        if ($livro && $livro->status === 'disponivel') {
            $livro->status = 'reservado';
            $livro->save();
        }

        return redirect()->route('reservas.index')->with('success', 'Reserva criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reserva $reserva)
    {
        $reserva->load(['livro', 'user']);
        return view('reservas.show', compact('reserva'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reserva $reserva)
    {
        $livros = Livro::all();
        $users = User::all();
        return view('reservas.edit', compact('reserva', 'livros', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reserva $reserva)
    {
        $request->validate([
            'livro_id' => 'required|exists:livros,id',
            'user_id' => 'required|exists:users,id',
            'data_reserva' => 'required|date',
            'status' => 'required|string|in:pendente,cancelada,concluida',
        ]);

        $oldStatus = $reserva->status;
        $newStatus = $request->input('status');

        $reserva->update($request->all());

        // Lógica para atualizar o status do livro quando a reserva muda
        $livro = Livro::find($reserva->livro_id);
        if ($livro) {
            // Se a reserva foi concluída ou cancelada e o livro estava 'reservado' por ela
            if (($newStatus === 'concluida' || $newStatus === 'cancelada') && $livro->status === 'reservado') {
                // Verificar se existem outras reservas pendentes para o mesmo livro
                $outrasReservasPendentes = Reserva::where('livro_id', $livro->id)
                                                    ->where('status', 'pendente')
                                                    ->where('id', '!=', $reserva->id)
                                                    ->exists();
                if (!$outrasReservasPendentes) {
                    // Se não houver mais reservas pendentes, o livro volta a ser disponível
                    $livro->status = 'disponivel';
                    $livro->save();
                }
            } elseif ($newStatus === 'pendente' && $livro->status === 'disponivel') {
                $livro->status = 'reservado';
                $livro->save();
            }
        }

        return redirect()->route('reservas.index')->with('success', 'Reserva atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reserva $reserva)
    {
        // Lógica para atualizar o status do livro ao excluir a reserva
        $livro = Livro::find($reserva->livro_id);
        if ($livro && $reserva->status === 'pendente' && $livro->status === 'reservado') {
            // Se o livro estava reservado por esta reserva e não há outras reservas pendentes, ele volta a ser disponível
            $outrasReservasPendentes = Reserva::where('livro_id', $livro->id)
                                                ->where('status', 'pendente')
                                                ->where('id', '!=', $reserva->id)
                                                ->exists();
            if (!$outrasReservasPendentes) {
                $livro->status = 'disponivel';
                $livro->save();
            }
        }

        $reserva->delete();
        return redirect()->route('reservas.index')->with('success', 'Reserva excluída com sucesso!');
    }
}