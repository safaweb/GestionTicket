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
use Carbon\Carbon;


class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getActions(): array
    {
        $actions = [
            Actions\ViewAction::make(),
        ];

        $ticketStatus = $this->record->statutDuTicket ? $this->record->statutDuTicket->name : null;
        if ($ticketStatus !== 'Non Résolu') {
            $actions[] = Actions\DeleteAction::make();
            $actions[] = Actions\ForceDeleteAction::make();
            $actions[] = Actions\RestoreAction::make();
        }

        if (Auth::user()->hasAnyRole(['Super Admin', 'Chef Projet', 'Employeur'])) {
            $actions[] = Actions\Action::make('Validation')
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
                    // Redirect back to the ticket view
                    return redirect()->route('filament.resources.tickets.view', $ticketId);
                });
                
                $actions[] = Actions\Action::make('Terminer')
                ->label('Terminer')
                ->visible(fn () => $this->record->validation_id === 1) // Show only if validation_id is 1
                ->form([
                    Forms\Components\Radio::make('status')
                        ->options([
                            'resolu' => 'Résolu',
                            'non_resolu' => 'Non Résolu',
                        ])
                        ->reactive()
                        ->required()
                        ->afterStateUpdated(function (callable $set, $state) {
                            $set('showCommentaire', $state === 'non_resolu');
                        }),
                    Forms\Components\Textarea::make('commentaire')
                        ->label('Commentaire')
                        ->visible(fn (callable $get) => $get('showCommentaire'))
                        ->required(fn (callable $get) => $get('showCommentaire')),
                    Forms\Components\DatePicker::make('date_debut')
                        ->label('Date de Début')
                        ->required()
                        ->default(fn () => Carbon::now()), // Optional: Default to current date
                    Forms\Components\DatePicker::make('date_fin')
                        ->label('Date de Fin')
                        ->required()
                        ->default(fn () => Carbon::now()->addDays(1)), // Optional: Default to 1 day after current date
                    Forms\Components\TextInput::make('nombre_heures')
                        ->label('Nombre d\'Heures')
                        ->numeric()
                        ->required(),
                ])
                ->action(function ($data) {
                    $ticketId = $this->record->id;
                    if (!isset($data['status'])) {
                        // Handle status error, if needed
                        return;
                    }
                    $commentaire = $data['commentaire'] ?? '';
                    if ($data['status'] === 'resolu') {
                        $this->changeTicketStatus($ticketId, 'Résolu', $commentaire, $data);
                        $this->saveValidation($ticketId, 3); // Set validation_id to 3
                    } elseif ($data['status'] === 'non_resolu') {
                        if (empty($commentaire)) {
                            $this->addError('commentaire', 'Vous devez spécifier un commentaire pour marquer le ticket comme non résolu.');
                            return; // Exit the method if commentaire is empty
                        }

                        $this->changeTicketStatus($ticketId, 'Non Résolu', $commentaire, $data);
                        $this->saveValidation($ticketId, 3); // Set validation_id to 3
                    }
                });
        }
        return $actions;    
    }

    protected function changeTicketStatus($ticketId, $newStatus, $commentaire = null, $data = null )
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->statutDuTicket()->associate(StatutDuTicket::where('name', $newStatus)->first());

        if ($commentaire !== null) {
            // Add comment to the ticket
            $formattedCommentaire = $commentaire . "\nLa date de début est: " . ($data['date_debut'] ?? 'Non spécifiée');
            $formattedCommentaire .= "\nDate de fin est: " . ($data['date_fin'] ?? 'Non spécifiée');
            $formattedCommentaire .= "\nNombre d'heures est: " . ($data['nombre_heures'] ?? 'Non spécifié');
            Commentaire::create([
                'ticket_id' => $ticketId,
                'user_id' => Auth::id(),
                'commentaire' => $formattedCommentaire,
            ]);
        }
        $ticket->save();
    }

    private function saveValidation($ticketId, $validationId) {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->validation_id = $validationId;
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
