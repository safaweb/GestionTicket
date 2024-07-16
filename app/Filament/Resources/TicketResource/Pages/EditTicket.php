<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Ticket;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action as NotificationAction;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getActions(): array
    {

        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Custom logic before saving, if needed
        return $data;
    }

    protected function afterSave(): void
    {
        // Get the form data
        $data = $this->form->getState();

        // Call the custom editTicket method
        $this->editTicket($data, $this->record->id);
    }

    protected function editTicket(array $data, $ticketId)
    {
        // Get the ticket
        $ticket = Ticket::findOrFail($ticketId);

        // Update the ticket
        $ticket->update($data);

        // Get the current user
        $currentUser = Auth::user();

   

        // Send notification to other relevant users
        if ($currentUser->hasAnyRole(['Admin Projet', 'Staff Projet', 'Super Admin', 'Client'])) {
            $receiver = User::where('projet_id', $currentUser->projet_id)
                            ->where('id', '!=', $currentUser->id)
                            ->get();
        } else {
            $receiver = User::whereHas('roles', function ($q) {
                $q->where('name', 'Admin Projet')
                    ->orWhere('name', 'Staff Projet')
                    ->orWhere('name', 'Super Admin');
            })->where('projet_id', $currentUser->projet_id)
              ->where('id', '!=', $currentUser->id)
              ->get();
        }

        // Send the notification to appropriate recipients
        Notification::make()
            ->title('Vous avez été assigné comme responsable d\'un ticket')
            ->actions([
                NotificationAction::make('Voir')
                    ->url(route('filament.resources.tickets.view', $ticket->id)),
            ])
            ->sendToDatabase($receiver);
    }
}
