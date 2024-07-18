<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class TicketAssignedNotification extends Notification implements ShouldQueue
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
                    ->subject('Nouveau ticket assigné')
                    ->line('Vous avez été assigné comme responsable du ticket : '  . $this->ticket->title)
                    ->line('Le client: ' . $this->ticket->owner->name)
                    ->line('Le projet: ' . $this->ticket->projet->name)
                    ->action('Voir le ticket', route('filament.resources.tickets.view', $this->ticket->id));
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
