<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class TicketCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;

    /**
     * Create a new notification instance.
     *
     * @param Ticket $ticket
     * @return void
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    { 
        return (new MailMessage)
                    ->subject('Votre Nouveau ticket créé')
                    ->line('Bonjour Mr/Mme  '. AUTH::user()->name.', ')
                    ->line('Votre ticket avec le numéro '. $this->ticket->id.'  a été créé avec succées ')                    
                    // ->line('Ticket: '. $this->ticket->title)
                    // ->line('Client: ' . $this->ticket->owner->name)
                    // ->line('Projet: ' . $this->ticket->projet->name)
                    ->line('Un des consultants du support client vous contactera bientôt.' )
                    ->line('Vous pouvez voir le sort de vos tickets et ajouter une autre tickets à travers le lien suivant : ' )
                    ->action(' Ajouter Ticket ', route('filament.resources.tickets.view', $this->ticket->id));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_title' => $this->ticket->title,
            'url' => route('filament.resources.tickets.view', $this->ticket->id),
        ];
    }
}
