<?php
namespace App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource;
use App\Filament\Resources\StatutDuTicketResource;
use App\Models\StatutDuTicket;
use Filament\Forms\Components\Button;
use App\Filament\Resources\TicketResource\RelationManagers\CommentairesRelationManager;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Ticket;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Forms;

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
            Actions\Action::make('sauvegarder')
            ->label('Refuser')
            ->modalHeading('Refuser le Ticket')
            ->form([
                Forms\Components\Textarea::make('commentaire')
                    ->label('Commentaire')
                    ->required()
            ])
            ->action('afterSave'), // Ensure 'afterSave' is handled uniquely*/
    ];}
    
    protected function afterSave(): void
    {
        $data = $this->form->getState();
        $ticketId = $this->record->id;
        // Check the radio button value for 'validation'
        if (isset($data['validation']) && $data['validation'] === 'accepter') {
            $this->changeTicketStatus($ticketId, 'ouvert');
        } elseif (isset($data['validation']) && $data['validation'] === 'refuser') {
            $this->changeTicketStatus($ticketId, 'Non Résolu');
            $this->notifyAssignedUser($ticketId);
        }
        // Optionally, perform other operations or save changes
         $this->editTicket($data, $ticketId); // You may choose to include this if needed
    }
    
    protected function changeTicketStatus($ticketId, $newStatus)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->statutDuTicket()->associate(StatutDuTicket::where('name', $newStatus)->first());
        $ticket->save();
    }

    protected function editTicket(array $data, $ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
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
            return User::where('societe_id', $currentUser->societe_id)
                        ->where('id', '!=', $currentUser->id)
                        ->get();
        } else {
            return User::whereHas('roles', function ($q) {
                $q->where('name', 'Chef Projet')
                ->orWhere('name', 'Employeur')
                ->orWhere('name', 'Super Admin');
            })->where('societe_id', $currentUser->societe_id)
            ->where('id', '!=', $currentUser->id)
            ->get();
        }
    }
    
}
