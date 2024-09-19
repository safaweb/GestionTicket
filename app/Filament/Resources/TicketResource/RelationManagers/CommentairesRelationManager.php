<?php
namespace App\Filament\Resources\TicketResource\RelationManagers;
use App\Filament\Resources\TicketResource;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Livewire\Component as Livewire;
class CommentairesRelationManager extends RelationManager
{
    protected static string $relationship = 'commentaires';
    protected static ?string $recordTitleAttribute = 'commentaire';
    
    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Forms\Components\RichEditor::make('commentaire')
                        ->required()
                        ->maxLength(255)
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'strike',
                            'link',
                            'heading', // Pour les titres
                            'subheading', // Pour les sous-titres (si supporté)
                            'redo',
                            'undo',
                            'blockquote',   // Citations
                            'codeBlock',    // Blocs de code
                            'orderedList', // Pour les listes numérotées
                            'bulletList', // Pour les listes à points
                        ]),
                    Forms\Components\FileUpload::make('attachments')
                        ->directory('commentaire-attachments/' . date('m-y'))
                        ->maxSize(2000)
                        ->enableDownload()
                        ->visible(fn ($record) => $record === null), // Hide after creation,     ,
                ])
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    Split::make([
                        TextColumn::make('user.name')
                            ->translateLabel()
                            ->weight('bold')
                            ->grow(false),
                        TextColumn::make('created_at')
                            ->translateLabel()
                            ->dateTime()
                            ->color('secondary'),
                    ]),
                    TextColumn::make('commentaire')
                        ->wrap()
                        ->html(),
                ]),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        return $data;
                    })
                    ->label('Ajouter un commentaire')
                    ->visible(function (Livewire $livewire) {
                        $ticket = $livewire->ownerRecord;
                        return !in_array($ticket->validation_id, [2, 3]);
                    })
                    ->after(function (Livewire $livewire) {
                        $ticket = $livewire->ownerRecord;
                        $currentUser = auth()->user();
                        
                        // Récupérer les utilisateurs de la même société via la table societe_user
                        $usersInSameSociete = User::whereIn('id', function ($query) use ($currentUser) {
                            $query->select('user_id')
                                ->from('societe_user')
                                ->where('societe_id', $currentUser->societe_id);
                        })
                        ->where('id', '!=', $currentUser->id)
                        ->get();
                        
                        // Ajouter l'utilisateur qui a créé le ticket
                        $ticketOwner = $ticket->owner;
                        
                        // Fusionner les destinataires en un tableau unique sans doublons
                        $receiver = $usersInSameSociete->push($ticketOwner)->unique();
                        
                        // Envoyer la notification aux destinataires appropriés
                        Notification::make()
                            ->title('Il y a un nouveau commentaire sur votre ticket')
                            ->actions([
                                Action::make('Voir')
                                    ->url(TicketResource::getUrl('view', ['record' => $ticket->id])),
                            ])
                            ->sendToDatabase($receiver);
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('attachment')->action(function ($record) {
                    return response()->download('storage/' . $record->attachments);
                })->hidden(fn ($record) => $record->attachments == ''),

                // Tables\Actions\EditAction::make()
                // ->modalHeading(fn ($record) => 'Modifier')
                // ->visible(function (Livewire $livewire) {
                //     $ticket = $livewire->ownerRecord;
                //     return !in_array($ticket->validation_id, [2, 3]);
                // }),
            ])
            ->bulkActions([]);
    }
}