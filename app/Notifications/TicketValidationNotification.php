<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;


class TicketValidationNotification extends Notification
{
    use Queueable;

    private $ticket;
    private $validation;
    private $commentaire;
    protected $newStatus;
    protected $validation_id;
    private $date_debut;
    private $date_fin;
    private $nombre_heures;
  
   
    public function __construct(Ticket $ticket, $newStatus ,  $validation = null, $commentaire = null, $validation_id, $date_debut = null, $date_fin = null, $nombre_heures = null)
    {
        $this->ticket = $ticket;
        $this->newStatus = $newStatus;
        $this->validation = $validation;
        $this->commentaire = $commentaire;
        $this->validation_id= $validation_id;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->nombre_heures = $nombre_heures;
   
    }
    

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'new_status' => $this->newStatus,
            'validation' => $this->validation,
            'commentaire' => $this->commentaire,
            'validation_id' => $this->validation_id,
          'date_debut' => $this->date_debut,
           'date_fin' => $this->date_fin,
            'nombre_heures' => $this->nombre_heures,
            
                ];
            }
    

    public function via($notifiable)
    {
        return ['mail' ,'database'];
    }
    
    

    public function toMail($notifiable)
    {
        $loginUrl = 'http://192.168.1.230:80/GestionTicket/public/admin/login';  
                    
        $mailMessage = new MailMessage();

        if ($this->validation === 'accepter') {
            $mailMessage->subject('Validation et Statut du Ticket')
                        ->line('Votre Ticket a été accepté.' )
                        ->line(' Nom du Ticket: ' . $this->ticket->title)
                        ->line('Nouveau Statut : ' . $this->newStatus)
                         ->action('Connectez-vous pour voir le Ticket',$loginUrl);
        } elseif ($this->validation === 'refuser') {
            $mailMessage->subject('Validation et Statut du Ticket')
                        ->line('Votre Ticket a été refusé.')
                        ->line(' Nom du Ticket: ' . $this->ticket->title)
                        ->line('Nouveau Statut : ' . $this->newStatus)
                        ->line('Commentaire: ' . $this->commentaire )
                        ->action('Connectez-vous pour voir le Ticket',$loginUrl);
        } elseif ($this->validation_id === 3 ) {
            $mailMessage->subject('Validation et Statut du Ticket')
                        ->line('Votre Ticket a été terminé.')
                        ->line(' Nom du Ticket: ' . $this->ticket->title)
                        ->line('Nouveau Statut : ' . $this->newStatus);
        if ($this->newStatus === 'Résolu') {
                $mailMessage ->line('Date de début : ' . $this->date_debut)
                ->line('Date de fin : ' . $this->date_fin)
                ->line('Nombre d\'heures : ' . $this->nombre_heures)
                            ->action('Connectez-vous pour voir le Ticket', $loginUrl);
             } elseif ($this->newStatus === 'Non Résolu') {
                $mailMessage  ->line('Date de début : ' . $this->date_debut)
                ->line('Date de fin : ' . $this->date_fin)
                ->line('Nombre d\'heures : ' . $this->nombre_heures)
                 ->line('Commentaire: ' . $this->commentaire )
                 ->action('Connectez-vous pour voir le Ticket',$loginUrl);
              }
           
        }

        //if ($this->commentaire) {
           // $mailMessage->line("Commentaire: {$this->commentaire}");
      ///  }

        return $mailMessage;
    }

}
  

