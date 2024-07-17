<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Projet;
use App\Models\User;

class ProjetPolicy
{
     /**
     * Determine whether the user can view any models.
    * public function viewAny(User $user): bool
    * {
    *    return $user->hasRole('Chef Projet');
    *}
    */
    
    /**
     * Determine whether the user can view any models.
     */
     public function viewAny(User $user): bool
     {
     //return $user->can('view-any Projet');
     return $user->hasRole('Chef Projet');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Projet $projet): bool
    {
        return $user->can('view Projet');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create Projet');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Projet $projet): bool
    {
        return $user->can('update Projet');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Projet $projet): bool
    {
        return $user->can('delete Projet');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Projet $projet): bool
    {
        return $user->can('restore Projet');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Projet $projet): bool
    {
        return $user->can('force-delete Projet');
    }
}
