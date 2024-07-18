<?php
namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use App\Models\StatutDuTicket;
use App\Models\Ticket;
use App\Models\Commentaire;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
            Actions\Action::make('Validation')
                ->label('Validation')
                ->form([
                    Forms\Components\Radio::make('validation')
                        ->options([
                            'accepter' => 'Accepter',
                            'refuser' => 'Refuser',
                        ])
                        ->reactive()
                        ->required()
                        ->afterStateUpdated(function (callable $set, $state) {
                            $set('showCommentaire', $state === 'refuser');
                        }),
                    Forms\Components\Textarea::make('commentaire')
                        ->label('Commentaire')
                        ->visible(fn (callable $get) => $get('showCommentaire'))
                        ->required(fn (callable $get) => $get('showCommentaire')),
                ])
                ->action(function ($data) {
                    $ticketId = $this->record->id;

                    if (!isset($data['validation'])) {
                        // Handle validation error, if needed
                        return;
                    }

                    if ($data['validation'] === 'accepter') {
                        $this->changeTicketStatus($ticketId, 'ouvert');
                    } elseif ($data['validation'] === 'refuser') {
                        if (!isset($data['commentaire']) || empty($data['commentaire'])) {
                            $this->addError('commentaire', 'Vous devez spécifier un commentaire pour refuser le ticket.');
                            return; // Exit the method if commentaire is empty
                        }

                        $this->changeTicketStatus($ticketId, 'Non Résolu', $data['commentaire']);
                        $this->notifyAssignedUser($ticketId);
                    }

                    // Optionally, perform other operations or save changes
                    // $this->editTicket($data, $ticketId); // You may choose to include this if needed
                }),
        ];
    }

    protected function changeTicketStatus($ticketId, $newStatus, $commentaire = null)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->statutDuTicket()->associate(StatutDuTicket::where('name', $newStatus)->first());
        
        if ($commentaire !== null) {
            // Add comment to the ticket
            Commentaire::create([
                'ticket_id' => $ticketId,
                'user_id' => Auth::id(),
                'commentaire' => $commentaire,
            ]);
        }

        $ticket->save();
    }

    protected function notifyAssignedUser($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
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
