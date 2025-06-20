<?php

namespace App\Policies;

use App\Models\Reserva;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReservaPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isLibrarian()) {
            return true; // Librarians have full access to reservations
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isLibrarian();
    }

    public function view(User $user, Reserva $reserva): bool
    {
        return $user->isLibrarian() || $user->id === $reserva->usuario_id;
    }

    public function create(User $user): bool
    {
        return $user->isUser(); // Only regular users can make reservations initially
    }

    public function update(User $user, Reserva $reserva): bool
    {
        return $user->isLibrarian() || ($user->isUser() && $user->id === $reserva->usuario_id);
    }

    public function delete(User $user, Reserva $reserva): bool
    {
        return $user->isLibrarian() || ($user->isUser() && $user->id === $reserva->usuario_id);
    }
}