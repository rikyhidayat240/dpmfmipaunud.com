<?php

namespace App\Policies;

use App\Models\Mention;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MentionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Mention $mention): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Mention $mention): bool
    {
        return $user->id === $mention->id_user || $user->role === 'Admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Mention $mention): bool
    {
        return $user->id === $mention->id_user || $user->role === 'Admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Mention $mention): bool
    {
        return $user->id === $mention->id_user || $user->role === 'Admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Mention $mention): bool
    {
        return $user->id === $mention->id_user || $user->role === 'Admin';
    }
}
