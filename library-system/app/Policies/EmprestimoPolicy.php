<?php

namespace App\Policies;

use App\Models\Emprestimo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EmprestimoPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isLibrarian()) {
            return true; // Librarians have full access to loans
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isLibrarian();
    }

    public function view(User $user, Emprestimo $emprestimo): bool
    {
        return $user->isLibrarian() || $user->id === $emprestimo->usuario_id;
    }

    public function create(User $user): bool
    {
        return $user->isLibrarian();
    }

    public function update(User $user, Emprestimo $emprestimo): bool
    {
        return $user->isLibrarian();
    }

    public function delete(User $user, Emprestimo $emprestimo): bool
    {
        return $user->isLibrarian();
    }

    public function renew(User $user, Emprestimo $emprestimo): bool
    {
        return $user->isLibrarian() || $user->id === $emprestimo->usuario_id;
    }
}