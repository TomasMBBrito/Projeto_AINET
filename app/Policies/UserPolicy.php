<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Apenas membros da direção podem gerir utilizadores.
     */
    public function manageUsers(User $user): bool
    {
        return $user->type === 'board';
    }

    /**
     * Um membro da direção não pode apagar-se a si próprio.
     */
    public function delete(User $user, User $target): bool
    {
        return $user->id !== $target->id && $user->type === 'board';
    }

    /**
     * Só pode promover/rebaixar se for direção e não ele próprio.
     */
    public function toggleBoard(User $user, User $target): bool
    {
        return $user->id !== $target->id && $user->type === 'board';
    }

    /**
     * Só direção pode bloquear outros.
     */
    public function block(User $user, User $target): bool
    {
        return $user->type === 'board' && $user->id !== $target->id;
    }
}
