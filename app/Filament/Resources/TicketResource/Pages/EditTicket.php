<?php
namespace App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource;
use App\Filament\Resources\StatutDuTicketResource;
use App\Models\StatutDuTicket;

use App\Filament\Resources\TicketResource\RelationManagers\CommentairesRelationManager;
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

    protected function afterSave(): void
    {
        $data = $this->form->getState();
        $this->editTicket($data, $this->record->id);
    }

    protected function editTicket(array $data, $ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
/*
        if ($data['accepter']) {
            $ticket->statuts_des_tickets_id = StatutDuTicket::where('name', 'En cours')->first()->id;
                } elseif ($data['refuser']) {
                    $ticket->statuts_des_tickets_id = StatutDuTicket::where('name', 'Non resolue')->first()->id;
            $ticket->commentaire = $data['commentaire'];
        }
*/
        $ticket->save();

        $currentUser = Auth::user();
        $receiver = $this->getNotificationRecipients($currentUser);

        Notification::make()
            ->title('Vous avez été assigné comme responsable d\'un ticket')
            ->actions([
                NotificationAction::make('Voir')
                    ->url(route('filament.resources.tickets.view', $ticket->id)),
            ])
            ->sendToDatabase($receiver);
    }

    private function getNotificationRecipients($currentUser)
    {
        if ($currentUser->hasAnyRole(['Super Admin', 'Chef Projet', 'Employeur'])) {
            return User::where('projet_id', $currentUser->projet_id)
                        ->where('id', '!=', $currentUser->id)
                        ->get();
        } else {
            return User::whereHas('roles', function ($q) {
                $q->where('name', 'Chef Projet')
                ->orWhere('name', 'Employeur')
                ->orWhere('name', 'Super Admin');
            })->where('projet_id', $currentUser->projet_id)
            ->where('id', '!=', $currentUser->id)
            ->get();
        }
    }
}
