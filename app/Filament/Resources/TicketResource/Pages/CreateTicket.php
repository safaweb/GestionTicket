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
    // Create the ticket
    $ticket = Ticket::create($data);

    // Fetch users with the 'Super Admin' role
    $superAdmins = User::whereHas('roles', function ($query) {
        $query->where('name', 'Super Admin');
    })->get();

    // Fetch 'Chef Projet' users who are associated with the project in the ticket
    $chefProjets = User::whereHas('roles', function ($query) {
        $query->where('name', 'Chef Projet');
    })->whereHas('projets', function ($query) use ($ticket) {
        $query->where('projet_id', $ticket->projet_id);
    })->get();

    // Merge the lists of users to notify
    $receivers = $superAdmins->merge($chefProjets);

    // Send the notification to appropriate recipients
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
   /* protected function handleRecordCreation(array $data): Ticket
    {
        $ticket = parent::handleRecordCreation($data);
        // Get the current user
       /* $currentUser = Auth::user();
        if ($currentUser->hasAnyRole(['Super Admin', 'Chef Projet', 'Employeur', 'Client'])) {
            $receiver = User::where('projet_id', $currentUser->projet_id)
                            ->where('id', '!=', $currentUser->id)
                            ->get();
        } else {
            // Send notification to users with specific roles, excluding current user
            $receiver = User::whereHas('roles', function ($q) {
                $q->where('name', 'Chef Projet')
                ->orWhere('name', 'Super Admin');
            })->where('projet_id', $currentUser->projet_id)
            ->where('id', '!=', $currentUser->id)
            ->get();
        }
        // Send the notification to appropriate recipients
        Notification::make()
            ->title('Il y a un nouveau ticket créé')
            ->actions([
                Action::make('Voir')
                    ->url(route('filament.resources.tickets.view', $ticket->id)),
            ])
            ->sendToDatabase($receiver);
        // send email for ticket creation
            foreach ($receiver as $user) {
                $user->notify(new TicketCreatedNotification($ticket));
            }
        return $ticket;
    }*/
}


/*$currentUser = Auth::user();
$projectUsersQuery = User::query()
    ->where('id', '!=', $currentUser->id)
    ->whereHas('projets', function ($query) use ($currentUser) {
        $query->where('projet_user.projet_id', $currentUser->projet_id);
    });

if ($currentUser->hasAnyRole(['Super Admin', 'Chef Projet', 'Employeur', 'Client'])) {
    $receiver = $projectUsersQuery->get();
} else {
    $receiver = $projectUsersQuery->whereHas('roles', function ($q) {
        $q->where('name', 'Chef Projet')
            ->orWhere('name', 'Super Admin');
    })->get();
}*/