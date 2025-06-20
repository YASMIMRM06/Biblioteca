<?php

namespace App\Policies;

use App\Models\Editora;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EditoraPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isLibrarian()) {
            return true; // Librarians can manage publishers
        }
        return false; // Regular users cannot manage publishers
    }

    public function viewAny(User $user): bool
    {
        return $user->isLibrarian() || $user->isUser(); // Both can view publishers (if listed somewhere)
    }

    public function view(User $user, Editora $editora): bool
    {
        return $user->isLibrarian() || $user->isUser();
    }

    public function create(User $user): bool
    {
        return $user->isLibrarian();
    }

    public function update(User $user, Editora $editora): bool
    {
        return $user->isLibrarian();
    }

    public function delete(User $user, Editora $editora): bool
    {
        return $user->isLibrarian();
    }
}