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
        $loginUrl = 'http://127.0.0.1:8000/admin/login';

        return (new MailMessage)
                    ->subject('Your Account Has Been Created')
                    ->line('Your account has been successfully created.')
                    ->line('Email: ' . $this->email)
                    ->line('Password: ' . $this->password)
                    ->action('Login', $loginUrl)
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [

                'email' => $this->email,
                'password' => $this->password,

        ];
    }
}
