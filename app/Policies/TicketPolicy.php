<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Ticket;
use App\Models\StatutDuTicket;
use App\Models\User;

class TicketPolicy
{
    /** Determine whether the user can view any models. */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /** Determine whether the user can view the model.*/
    public function view(User $user, Ticket $ticket): bool
    {
        // The Admin Projet can view tickets that are assigned to their specific projet.
        if ($user->hasRole('Chef Projet')) {
            return $user->id == $ticket->owner_id || $user->projets->contains($ticket->projet_id);
        }
        // The staff projet can view tickets that have been assigned to them.
        if ($user->hasRole('Employeur')) {
            return $user->id == $ticket->owner_id ||  $ticket->responsible_id == $user->id;
        }
        // The user can view their own ticket
        return $user->id == $ticket->owner_id;
    }

    /** Determine whether the user can create models. */
    public function create(User $user): bool
    {
        return true;
    }

    /**Determine whether the user can update the model. */
    public function update(User $user, Ticket $ticket): bool
    {
            // Only allow specific roles to update tickets
            if ($ticket->statut_des_tickets_id != StatutDuTicket::OUVERT ) {
                return $user->hasAnyRole(['Super Admin', 'Chef Projet', 'Employeur']);
                return $this->view($user, $ticket);
            }
            return false;
    }
         // Check if the ticket status is 'OUVERT' for editing permissions
    /*if ($ticket->statut_des_tickets_id != StatutDuTicket::OUVERT || $user->hasRole('Client') ) { return false; } */


    /** Determine whether the user can delete the model. */
    public function delete(User $user, Ticket $ticket): bool
    {
        if ($ticket->statut_des_tickets_id != StatutDuTicket::OUVERT) {
            return false;
        }
        return $user->id == $ticket->owner_id;
    }

    /**Determine whether the user can restore the model. */
    public function restore(User $user, Ticket $ticket): bool
    {
        return $user->id == $ticket->owner_id;
    }
    /**Determine whether the user can permanently delete the model. */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return $user->id == $ticket->owner_id;
    }
}
