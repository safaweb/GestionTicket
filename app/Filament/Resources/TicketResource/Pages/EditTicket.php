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
use App\Notifications\TicketAssignedNotification;

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
if ($currentUser->hasAnyRole(['Super Admin', 'Chef Projet', 'Employeur', 'Client'])) {
    $receiver = User::where('projet_id', $currentUser->societe_id)
                    ->where('id', '!=', $currentUser->id)
                    ->get();
} else {
    $receiver = User::whereHas('roles', function ($q) {
        $q->where('name', 'Employeur');
    })->where('projet_id', $currentUser->societe_id)
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

 // Send the notification to appropriate recipients
 foreach ($receiver as $user) {
    $user->notify(new TicketAssignedNotification($ticket));
}

}
}
