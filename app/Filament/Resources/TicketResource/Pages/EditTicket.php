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
use App\Notifications\TicketAssignedNotification;
use App\Notifications\StatutDuTicketModifie;
use App\Notifications\TicketValidationNotification;

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
                       // ->required()
                        ->afterStateUpdated(function (callable $set, $state) {
                            $set('showCommentaire', $state === 'refuser');
                        }),
                    Forms\Components\Textarea::make('commentaire')
                        ->label('Commentaire')
                        ->visible(fn (callable $get) => $get('showCommentaire'))
                        ->required(fn (callable $get) => $get('showCommentaire')),
                ])
                ->action(function ($data) {
                    $ticket = Ticket::findOrFail($this->record->id);
                    if (!isset($data['validation'])) {
                        return;
                    }
                    if ($data['validation'] === 'accepter') {
                        $ticket->statuts_des_tickets_id = StatutDuTicket::OUVERT;
                        $ticket->approved_at = Carbon::now();
                        $ticket->validation_id = 1;// Enregistrer la date actuelle dans approved_at
                        $ticket->save();
                        $ticketOwner = $ticket->owner; // Assumes there is a 'user' relationship
                        $ticketOwner->notify(new TicketValidationNotification($ticket, $data['validation']));
                        // Logique pour envoyer une notification à l'utilisateur assigné
                        $ticket->owner->notify(new StatutDuTicketModifie($ticket, $ticket->statutDuTicket->name));
                    } elseif ($data['validation'] === 'refuser') {
                        if (empty($data['commentaire'])) {
                            $this->addError('commentaire', 'Vous devez spécifier un commentaire pour refuser le ticket.');
                            return;
                        }
                        $ticket->statuts_des_tickets_id = StatutDuTicket::NONRESOLU;
                        $ticket->approved_at = Carbon::now();
                        $ticket->validation_id = 2;// Enregistrer la date actuelle dans approved_at
                        $ticket->save();
                        Commentaire::create([
                            'ticket_id' => $ticket->id,
                            'user_id' => Auth::id(),
                            'commentaire' => "\nVotre ticket est refusé car " . $data['commentaire'],
                        ]);
                        // Notify the ticket creator about the validation status
                        $ticketOwner = $ticket->owner; // Assumes there is a 'user' relationship
                        $ticketOwner->notify(new TicketValidationNotification($ticket, $data['validation'], $data['commentaire'] ?? null));
                        // Logique pour envoyer une notification à l'utilisateur assigné
                        $ticket->owner->notify(new StatutDuTicketModifie($ticket, $ticket->statutDuTicket->name));
                    }
                    // Redirect to the view page
                    $this->redirect(route('filament.resources.tickets.view', $ticket->id));
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
                        ->required(),])
                ->action(function ($data) {
                    $ticketId = $this->record->id;
                    if (!isset($data['status'])) {
                        return;
                    }
                    // Set validation_id to 3
                    $this->saveValidation($ticketId, 3);
                    // Set the solved_at date to now
                    $ticket = Ticket::findOrFail($ticketId);
                    $ticket->solved_at = Carbon::now();
                    if ($data['status'] === 'resolu') {
                        $this->changeTicketStatus($ticketId, 'Résolu', null, $data);
                    } elseif ($data['status'] === 'non_resolu') {
                        if (empty($data['commentaire'])) {
                            $this->addError('commentaire', 'Vous devez spécifier un commentaire pour marquer le ticket comme non résolu.');
                            return;
                        }
                        $this->changeTicketStatus($ticketId, 'Non Résolu', $data['commentaire'], $data);
                    }
                    
                    $ticket->save();
                    // Redirect to the view page
                    $this->redirect(route('filament.resources.tickets.view', $ticket->id));
                });
        }
        return $actions;    
    }
    /**
     * Change the status of the ticket and optionally add a comment.
     * @param int $ticketId
     * @param string $newStatus
     * @param string|null $commentaire
     */

    protected function changeTicketStatus($ticketId, $newStatus, $commentaire = null, $data = null)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->statutDuTicket()->associate(StatutDuTicket::where('name', $newStatus)->first());
        if ($ticket->validation_id === 3 && $newStatus === 'Non Résolu' && $commentaire !== null) {
            $formattedCommentaire = "\nVotre ticket est $newStatus";
            $formattedCommentaire .= " car $commentaire". "<br>";
            $formattedCommentaire .= " la date de début est: " . ($data['date_debut'] ?? 'Non spécifiée'). "<br>";
            $formattedCommentaire .= "La date de fin est: " . ($data['date_fin'] ?? 'Non spécifiée'). "<br>";
            $formattedCommentaire .= "Le nombre d'heures est: " . ($data['nombre_heures'] ?? 'Non spécifié'). "<br>";
        } elseif ($ticket->validation_id === 3 && $newStatus === 'Résolu') {
            $formattedCommentaire = "\nVotre ticket est $newStatus". "<br>";
            $formattedCommentaire .= "La date de début est: " . ($data['date_debut'] ?? 'Non spécifiée'). "<br>";
            $formattedCommentaire .= "La date de fin est: " . ($data['date_fin'] ?? 'Non spécifiée'). "<br>";
            $formattedCommentaire .= "Le nombre d'heures est: " . ($data['nombre_heures'] ?? 'Non spécifié'). "<br>";
        } 
        if (isset($formattedCommentaire)) {
            Commentaire::create([
                'ticket_id' => $ticketId,
                'user_id' => Auth::id(),
                'commentaire' => $formattedCommentaire,
            ]);
        }
        $ticket->save();
    }

    /**
     * Get the list of users who should receive the notification based on roles and project.
     * @param User $currentUser
     * @return \Illuminate\Database\Eloquent\Collection
     */

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
            foreach ($receiver as $user) {
                $user->notify(new TicketAssignedNotification($ticket));
            }
    }

    private function getNotificationRecipients($currentUser)
    {
        if ($currentUser->hasAnyRole(['Super Admin', 'Chef Projet', 'Employeur'])) {
            return User::where('societe_id', $currentUser->societe_id)
                        ->where('id', '!=', $currentUser->id)
                        ->get();
        } else {
            return User::whereHas('roles', function ($q) {
                $q->where('name', 'Employeur');              
            })->where('societe_id', $currentUser->societe_id)
            ->where('id', '!=', $currentUser->id)
            ->get();
        }
        // Send the notification to appropriate recipients
        foreach ($receiver as $user) {
        $user->notify(new TicketAssignedNotification($ticket));
        }
    }
}   