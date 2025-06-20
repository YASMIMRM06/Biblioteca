<?php

namespace App\Policies;

use App\Models\Livro;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LivroPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isLibrarian()) {
            return true; // Librarians can manage books
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isLibrarian() || $user->isUser(); // Both roles can view books
    }

    public function view(User $user, Livro $livro): bool
    {
        return $user->isLibrarian() || $user->isUser();
    }

    public function create(User $user): bool
    {
        return $user->isLibrarian();
    }

    public function update(User $user, Livro $livro): bool
    {
        return $user->isLibrarian();
    }

    public function delete(User $user, Livro $livro): bool
    {
        return $user->isLibrarian();
    }
}