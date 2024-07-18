<?php

namespace App\Notifications;

use App\Models\Projet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ProjectAttachedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;

    /**
     * Create a new notification instance.
     *
     * @param Projet $projet
     * @return void
     */
    public function __construct(Projet $ticket)
    {
        $this->projet = $projet;
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
                    ->subject('Nouveau projet attaché')
                    ->line('Vous avez été assigné comme responsable du projet :' . $this->projet->title)
                    ->line('La société: ' . $this->société->owner->name)
                    ->action('Voir le projet', route('filament.resources.projets.view', $this->projet->id));
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
            'projet_id' => $this->projet->id,
            'projet_title' => $this->projet->title,
            'url' => route('filament.resources.tickets.view', $this->projet->id),
        ];
    }
}
