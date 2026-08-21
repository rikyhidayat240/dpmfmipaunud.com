<?php

namespace App\Policies;

use App\Models\Aspirasi;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AspirasiPolicy
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
    public function view(User $user, Aspirasi $aspirasi): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Inti', 'Komisi 3']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Aspirasi $aspirasi): bool
    {
        return in_array($user->role, ['Admin', 'Inti', 'Komisi 3']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Aspirasi $aspirasi): bool
    {
        return in_array($user->role, ['Admin', 'Komisi 3']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Aspirasi $aspirasi): bool
    {
        return in_array($user->role, ['Admin', 'Komisi 3']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Aspirasi $aspirasi): bool
    {
        return in_array($user->role, ['Admin', 'Komisi 3']);
    }
}
