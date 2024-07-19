<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketValidationNotification extends Notification
{
    use Queueable;

    private $ticket;
    private $validation;
    private $commentaire;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ticket, $validation, $commentaire = null)
    {
        $this->ticket = $ticket;
        $this->validation = $validation;
        $this->commentaire = $commentaire;
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
        $mailMessage = new MailMessage();

        if ($this->validation === 'accepter') {
            $mailMessage->subject('Validation de votre ticket')
                        ->line('Votre ticket : ' . $this->ticket->title . '  a été accepté.' );
        } elseif ($this->validation === 'refuser') {
            $mailMessage->subject('Validation de votre ticket')
                        ->line('Votre ticket : ' . $this->ticket->title . ' a été refusé.');
        }

        if ($this->commentaire) {
            $mailMessage->line("Commentaire: {$this->commentaire}");
        }

        return $mailMessage;
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
            'validation' => $this->validation,
            'commentaire' => $this->commentaire,
        ];
    }
}
