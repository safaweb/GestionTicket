<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Ticket;
use App\Models\StatutDuTicket;
use App\Models\User;

class TicketPolicy
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
    public function view(User $user, Ticket $ticket): bool
    {
        // The Admin Projet can view tickets that are assigned to their specific projet.
        if ($user->hasRole('Admin Projet')) {
            return $user->id == $ticket->owner_id || $ticket->projet_id == $user->projet_id;
        }

        // The staff projet can view tickets that have been assigned to them.
        if ($user->hasRole('Staff Projet')) {
            return $user->id == $ticket->owner_id ||  $ticket->responsible_id == $user->id;
        }

        // The user can view their own ticket
        return $user->id == $ticket->owner_id;
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
    public function update(User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        if ($ticket->statut_des_tickets_id != StatutDuTicket::OPEN) {
            return false;
        }

        return $user->id == $ticket->owner_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ticket $ticket): bool
    {
        return $user->id == $ticket->owner_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return $user->id == $ticket->owner_id;
    }
}
