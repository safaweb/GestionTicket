<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $email;
    protected $password;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $loginUrl = 'http://192.168.1.230:80/GestionTicket/public/admin/login';
        return (new MailMessage)
                    ->subject('Votre compte a été créé')
                    ->line('Votre compte a été créé avec succès.')
                    ->line('Email : ' . $this->email)
                    ->line('Mot de passe : ' . $this->password)
                    ->line('Veuillez le modifier dans ton profile!')
                    ->action('Se connecter', $loginUrl)
                    ->line('Merci d\'utiliser notre application !');
    }

    public function toArray($notifiable)
    {
        return [
                'email' => $this->email,
                'password' => $this->password,
        ];
    }
}
