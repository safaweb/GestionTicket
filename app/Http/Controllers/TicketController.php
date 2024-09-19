<?php
use Illuminate\Notifications\DatabaseNotification;

class TicketController extends Controller
{
    public function viewTicket($ticketId, $notificationId)
    {
        // Find the notification by its ID and mark it as read
        $notification = DatabaseNotification::find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }

        // Redirect the user to the ticket view page (or display the ticket)
        return redirect()->route('filament.resources.tickets.view', $ticketId);
    }
}
