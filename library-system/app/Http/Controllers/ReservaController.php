<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Livro;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservaController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Reserva::class);
        $reservas = Reserva::with(['livro', 'usuario'])->paginate(10);
        return view('reservations.index', compact('reservas'));
    }

    // For "Fazer Reserva"
    public function create()
    {
        $this->authorize('create', Reserva::class);
        $livros = Livro::all(); // User can reserve any book, availability checked in store
        return view('reservations.create', compact('livros'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Reserva::class);

        $request->validate([
            'livro_id' => 'required|exists:livros,id',
        ]);

        $livro = Livro::findOrFail($request->livro_id);

        // Check if the user already has an active reservation for this book
        $existingReservation = Reserva::where('usuario_id', auth()->id())
                                    ->where('livro_id', $request->livro_id)
                                    ->where('status', 'pendente')
                                    ->first();

        if ($existingReservation) {
            return redirect()->back()->with('error', 'You already have an active reservation for this book.');
        }

        Reserva::create([
            'livro_id' => $request->livro_id,
            'usuario_id' => auth()->id(),
            'data_reserva' => Carbon::now(),
            'status' => 'pendente',
        ]);

        // Optional: If a book has 0 physical copies available but is reserved, update its status
        if ($livro->qtd_exemplares === 0 && $livro->status !== 'reservado') {
             $livro->update(['status' => 'reservado']);
        }

        return redirect()->route('reservations.my')->with('success', 'Book reserved successfully.');
    }

    // For "Visualizar Minhas Reservas"
    public function myReservations()
    {
        $user = auth()->user();
        $reservas = $user->reservas()->with('livro')->paginate(10);
        return view('reservations.my_reservations', compact('reservas'));
    }

    // Librarian actions to manage reservations (e.g., approve/cancel)
    public function edit(Reserva $reservation)
    {
        $this->authorize('update', $reservation);
        return view('reservations.edit', compact('reservation'));
    }

    public function update(Request $request, Reserva $reservation)
    {
        $this->authorize('update', $reservation);
        $request->validate([
            'status' => 'required|string|in:pendente,aprovada,cancelada,concluida',
        ]);

        $reservation->update(['status' => $request->status]);

        // If reservation is approved and book is available, perhaps change book status
        if ($request->status === 'aprovada' && $reservation->livro->qtd_exemplares > 0) {
            // This might indicate the book is now on hold for this user
        }

        return redirect()->route('reservations.index')->with('success', 'Reservation updated successfully.');
    }

    public function destroy(Reserva $reservation)
    {
        $this->authorize('delete', $reservation);
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Reservation deleted successfully.');
    }
}