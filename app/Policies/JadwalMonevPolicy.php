<?php

namespace App\Policies;

use App\Models\JadwalMonev;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JadwalMonevPolicy
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
    public function view(User $user, JadwalMonev $jadwalMonev): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Inti', 'Komisi 4']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, JadwalMonev $jadwalMonev): bool
    {
        return in_array($user->role, ['Admin', 'Inti', 'Komisi 4']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, JadwalMonev $jadwalMonev): bool
    {
        return in_array($user->role, ['Admin', 'Inti', 'Komisi 4']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, JadwalMonev $jadwalMonev): bool
    {
        return in_array($user->role, ['Admin', 'Inti', 'Komisi 4']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, JadwalMonev $jadwalMonev): bool
    {
        return in_array($user->role, ['Admin', 'Inti', 'Komisi 4']);
    }
}
