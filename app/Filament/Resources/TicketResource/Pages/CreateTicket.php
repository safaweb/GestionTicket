<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Ticket;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    /**
     * Préparer les données avant de les enregistrer dans la base de données.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['owner_id'] = auth()->id();
        $data['statuts_des_tickets_id'] = 1;

        return $data;
    }

    /**
     * Gérer la création du ticket et envoyer une notification après la création.
     */
    protected function handleRecordCreation(array $data): Ticket
    {
        $ticket = parent::handleRecordCreation($data);

        // Get the current user
        $currentUser = Auth::user();

        if ($currentUser->hasAnyRole(['Admin Projet', 'Staff Projet', 'Super Admin', 'Client'])) {
            $receiver = User::where('projet_id', $currentUser->projet_id)
                            ->where('id', '!=', $currentUser->id)
                            ->get();
        } else {
            // Send notification to users with specific roles, excluding current user
            $receiver = User::whereHas('roles', function ($q) {
                $q->where('name', 'Admin Projet')
                    ->orWhere('name', 'Staff Projet')
                    ->orWhere('name', 'Super Admin')
                    ->orWhere('name', 'Client');
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

        return $ticket;
    } 
}
