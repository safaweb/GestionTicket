<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Notifications\StatutDuTicketModifie;
use App\Notifications\UserCreated;
use App\Notifications\TicketAssignedNotification;

class EmailTestController extends Controller
{
    public function sendStatutDuTicketModifie()
    {
        $owner = User::find(1); // Replace with an existing user ID
        $owner->notify(new StatutDuTicketModifie());
        return 'Statut Du Ticket Modifie email sent!';
    }

    public function sendUserCreated()
    {
        $owner = User::find(1); // Replace with an existing user ID
        $owner->notify(new UserCreated());
        return 'Creation Ticket email sent!';
    }

    public function sendTicketAssigned()
    {
        $user = User::find(1); // Replace with an existing user ID
        $ticket = Ticket::find(1); // Replace with an existing ticket ID
        $user->notify(new TicketAssignedNotification($ticket));
        return 'Ticket Assigned email sent!';
    }
}
