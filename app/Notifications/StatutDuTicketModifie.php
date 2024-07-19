<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;

class StatutDuTicketModifie extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;
    protected $newStatus;

    public function __construct(Ticket $ticket, $newStatus)
    {
        $this->ticket = $ticket;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $loginUrl = 'http://127.0.0.1:8000/admin/login';
        return (new MailMessage)
                    ->subject('Statut du Ticket Modifié')
                    ->line('Le statut de votre ticket a été modifié.')
                    ->line(' Nom du Ticket: ' . $this->ticket->title)
                    ->line('Nouveau Statut : ' . $this->newStatus)
                    ->action('Connectez-vous pour voir le ticket',$loginUrl);
                    
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'new_status' => $this->newStatus,
        ];
    }


}
