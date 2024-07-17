<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Societe;
use App\Models\User;

class SocietePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any Societe');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Societe $societe): bool
    {
        return $user->can('view Societe');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create Societe');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Societe $societe): bool
    {
        return $user->can('update Societe');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Societe $societe): bool
    {
        return $user->can('delete Societe');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Societe $societe): bool
    {
        return $user->can('restore Societe');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Societe $societe): bool
    {
        return $user->can('force-delete Societe');
    }
}
