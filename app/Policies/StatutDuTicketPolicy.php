<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\StatutDuTicket;
use App\Models\User;

class StatutDuTicketPolicy
{
    /**Determine whether the user can view any models.*/
    public function viewAny(User $user): bool
    {
        return $user->can('view-any StatutDuTicket');
    }

    /** Determine whether the user can view the model.*/
    public function view(User $user, StatutDuTicket $statutduticket): bool
    {
        return $user->can('view StatutDuTicket');
    }

    /** Determine whether the user can create models. */
    public function create(User $user): bool
    {
        return $user->can('create StatutDuTicket');
    }

    /** Determine whether the user can update the model.*/
    public function update(User $user, StatutDuTicket $statutduticket): bool
    {
        return $user->can('update StatutDuTicket');
    }

    /** Determine whether the user can delete the model. */
    public function delete(User $user, StatutDuTicket $statutduticket): bool
    {
        return $user->can('delete StatutDuTicket');
    }

    /** Determine whether the user can restore the model.*/
    public function restore(User $user, StatutDuTicket $statutduticket): bool
    {
        return $user->can('restore StatutDuTicket');
    }

    /**Determine whether the user can permanently delete the model.*/
    public function forceDelete(User $user, StatutDuTicket $statutduticket): bool
    {
        return $user->can('force-delete StatutDuTicket');
    }
}
