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
                    $ticket = Ticket::findOrFail($this->record->id);
                    if (!isset($data['validation'])) {
                        return;
                    }
                    if ($data['validation'] === 'accepter') {
                        $ticket->statuts_des_tickets_id = StatutDuTicket::OUVERT;
                        $ticket->approved_at = Carbon::now();
                        $ticket->validation_id = 1;// Enregistrer la date actuelle dans approved_at
                        $ticket->save();
                        $this->notifyTicketOwner($ticket, $data, 'accepter');
                    } elseif ($data['validation'] === 'refuser') {
                        if (empty($data['commentaire'])) {
                            $this->addError('commentaire', 'Vous devez spécifier un commentaire pour refuser le ticket.');
                            return;
                        }
                        $ticket->statuts_des_tickets_id = StatutDuTicket::NONRESOLU;
                        $ticket->approved_at = Carbon::now();
                        $ticket->validation_id = 2;// Enregistrer la date actuelle dans approved_at
                        $ticket->save();
                        $this->notifyTicketOwner($ticket, $data, 'refuser');
                        Commentaire::create([
                            'ticket_id' => $ticket->id,
                            'user_id' => Auth::id(),
                            'commentaire' => "\nVotre ticket est refusé car " . $data['commentaire'],
                        ]);
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
                        ->required(),
                ])
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

    protected function changeTicketStatus($ticketId, $newStatus, $commentaire = null, $data = null)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->statutDuTicket()->associate(StatutDuTicket::where('name', $newStatus)->first());
    
        if ($ticket->validation_id === 3) {
            $formattedCommentaire = "\nVotre ticket est $newStatus";
            if ($newStatus === 'Non Résolu' && $commentaire !== null) {
                $formattedCommentaire .= " car $commentaire";
            }
            $formattedCommentaire .= "<br>La date de début est: " . ($data['date_debut'] ?? 'Non spécifiée');
            $formattedCommentaire .= "<br>La date de fin est: " . ($data['date_fin'] ?? 'Non spécifiée');
            $formattedCommentaire .= "<br>Le nombre d'heures est: " . ($data['nombre_heures'] ?? 'Non spécifié');
            
            Commentaire::create([
                'ticket_id' => $ticketId,
                'user_id' => Auth::id(),
                'commentaire' => $formattedCommentaire,
            ]);
        }
    
        $ticket->save();
        $this->notifyTicketOwner($ticket, $data, $newStatus);
        $ticketOwner = $ticket->owner; // Assumes there is a 'user' relationship
        $ticketOwner->notify(new TicketValidationNotification(
            $ticket, 
            $newStatus, 
            $data['validation'] ?? null, 
            $commentaire, 
            3, 
            $data['date_debut'] ?? null, 
            $data['date_fin'] ?? null, 
            $data['nombre_heures'] ?? null,
            
        ));
     
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
        foreach ($receiver as $user) {
            $user->notify(new TicketAssignedNotification($ticket));
        }
    }

    private function getNotificationRecipients($currentUser)
    {
        if ($currentUser->hasAnyRole(['Super Admin', 'Chef Projet', 'Employeur'])) {
            return User::where('projet_id', $currentUser->projet_id)
                        ->where('id', '!=', $currentUser->id)
                        ->get();
        } else {
            return User::whereHas('roles', function ($q) {
                $q->where('name', 'Employeur')
                  ->orWhere('name', 'Employeur')
                  ->orWhere('name', 'Super Admin');            
            })->where('projet_id', $currentUser->projet_id)
              ->where('id', '!=', $currentUser->id)
              ->get();
        }
    }

    protected function notifyTicketOwner($ticket, $data, $newStatus)
    {
        $ticketOwner = $ticket->owner;
        \Log::info('Notification Data: ', $data);

    
        if ($ticketOwner) {
            $title = '';
            $body = '';
    
            // Determine the title and body based on the validation status
            switch ($newStatus) {
                case 'accepter':
                    $title = 'Votre ticket a été accepté';
                    break;
    
                case 'refuser':
                    $title = 'Votre ticket a été refusé';
                    $body = "Commentaire: " . ($data['commentaire'] ?? 'Aucun commentaire');
                    break;
    
                case 'Résolu':
                    $title = 'Votre ticket a été terminé';
                    $body = "Le ticket a été marqué comme résolu.<br>La date de début est: " . ($data['date_debut'] ?? 'Non spécifiée');
                    $body .= "<br>La date de fin est: " . ($data['date_fin'] ?? 'Non spécifiée');
                    $body .= "<br>Le nombre d'heures est: " . ($data['nombre_heures'] ?? 'Non spécifié');
                    break;
    
                case 'Non Résolu':
                    $title = 'Votre ticket a été terminé';
                    $body = "Le ticket a été marqué comme non résolu.<br>La date de début est: " . ($data['date_debut'] ?? 'Non spécifiée');
                    $body .= "<br>La date de fin est: " . ($data['date_fin'] ?? 'Non spécifiée');
                    $body .= "<br>Le nombre d'heures est: " . ($data['nombre_heures'] ?? 'Non spécifié');
                    $body .= "<br>Commentaire: " . ($data['commentaire'] ?? 'Aucun commentaire');
                    break;
    
                default:
                    \Log::error('Invalid validation status for ticket ID: ' . $ticket->id);
                    return;
            }
    
            // Log the body for debugging
            \Log::info('Notification Body: ' . $body);
            \Log::info('Date de Début: ' . ($data['date_debut'] ?? 'Non spécifiée'));
            \Log::info('Date de Fin: ' . ($data['date_fin'] ?? 'Non spécifiée'));
            \Log::info('Nombre d\'Heures: ' . ($data['nombre_heures'] ?? 'Non spécifié'));


           
    
            // Send notification to the database
            Notification::make()
                ->title($title)
                ->body($body)
                ->actions([
                    NotificationAction::make('Voir')
                        ->url(route('filament.resources.tickets.view', $ticket->id)),
                ])
                ->sendToDatabase([$ticketOwner]);
    
            // Optionally send notification via other channels if needed
           $ticketOwner->notify(new TicketValidationNotification(
            $ticket,
           $ticket->statutDuTicket->name,
           $newStatus,
           3,
           $data['commentaire'] ?? null,
           $data['validation_id'] ?? null,
            
        ));
            
        }
  

    }
    
    
}
