<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Ticket;
use App\Models\User;
use Filament\Pages\Actions;
use Filament\Notifications\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Notifications\TicketCreatedNotification;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    /**Préparer les données avant de les enregistrer dans la base de données. */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['owner_id'] = auth()->id();
        $data['statuts_des_tickets_id'] = 5;
        $data['qualification_id'] = 1;
        $data['validation_id'] = 4;
        return $data;
    }

    /**Gérer la création du ticket et envoyer une notification après la création.*/
    protected function handleRecordCreation(array $data): Ticket
    {
         
        $client= User::where('id', $data['owner_id'])->first();       
        $clientCollection = collect([$client]); 
        
        $ticket = Ticket::create($data);
        $superAdmins = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('name', 'Super Admin');
        })->get();
        $receivers = $superAdmins->merge($clientCollection);
        $chefProjets = User::with(['roles', 'projets' => function ($query) use ($ticket) {
            $query->where('projet_id', $ticket->projet_id);
             }])->whereHas('roles', function ($query) {
            $query->where('name', 'Chef Projet');
        })->get();
        $receivers = $receivers->merge($chefProjets);  
        Notification::make()
            ->title('Il y a un nouveau ticket créé')
            ->actions([
                Action::make('Voir')
                    ->url(route('filament.resources.tickets.view', $ticket->id)),
            ])
            ->sendToDatabase($receivers);
        // send email for ticket creation
        foreach ($receivers as $user) {
            $user->notify(new TicketCreatedNotification($ticket));
        }
        return $ticket;
    }


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