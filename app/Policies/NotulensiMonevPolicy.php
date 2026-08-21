<?php

namespace App\Policies;

use App\Models\NotulensiMonev;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NotulensiMonevPolicy
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
    public function view(User $user, NotulensiMonev $notulensiMonev): bool
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
    public function update(User $user, NotulensiMonev $notulensiMonev): bool
    {
        return in_array(str($user->id), $notulensiMonev->tim_monev) || in_array($user->role, ['Admin', 'Komisi 4']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, NotulensiMonev $notulensiMonev): bool
    {
        return in_array($user->role, ['Admin', 'Komisi 4']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, NotulensiMonev $notulensiMonev): bool
    {
        return in_array($user->role, ['Admin', 'Komisi 4']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, NotulensiMonev $notulensiMonev): bool
    {
        return in_array($user->role, ['Admin', 'Komisi 4']);
    }
}
