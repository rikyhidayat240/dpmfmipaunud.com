<?php

namespace App\Policies;

use App\Models\KategoriAspirasi;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class KategoriAspirasiPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Inti', 'Komisi 3']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, KategoriAspirasi $kategoriAspirasi): bool
    {
        return in_array($user->role, ['Admin', 'Inti', 'Komisi 3']);
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
    public function update(User $user, KategoriAspirasi $kategoriAspirasi): bool
    {
        return in_array($user->role, ['Admin', 'Inti', 'Komisi 3']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, KategoriAspirasi $kategoriAspirasi): bool
    {
        return in_array($user->role, ['Admin', 'Komisi 3']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, KategoriAspirasi $kategoriAspirasi): bool
    {
        return in_array($user->role, ['Admin', 'Komisi 3']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, KategoriAspirasi $kategoriAspirasi): bool
    {
        return in_array($user->role, ['Admin', 'Komisi 3']);
    }
}
