<?php
namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers\CommentairesRelationManager;
use App\Models\Priority;
use App\Models\ProblemCategory;
use App\Models\Pays;
use App\Models\Ticket;
use App\Models\StatutDuTicket;
use App\Models\Projet;
use App\Models\Societe;
use App\Models\User;
use App\Models\Qualification;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([

                    Forms\Components\Select::make('qualification_id')
                        ->label(__('Qualifications'))
                        ->options(Qualification::all()
                        ->pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    Forms\Components\Select::make('projet_id')
                        ->label(__('Projets'))
                        //->options(Projet::all()
                         //   ->pluck('name', 'id'))
                         ->options(function (callable $get) {
                            $user = auth()->user();
                            $societeId = $user->societe_id; // Assuming the user model has a societe_id attribute
                            return Projet::where('societe_id', $societeId)->pluck('name', 'id');
                        })

                        ->searchable()
                        ->required()
                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                            $projet = Projet::find($state);
                        /*  if ($projet) {
                                $problemCategoryId = (int) $get('problem_category_id');
                                if ($problemCategoryId && $problemCategory = ProblemCategory::find($problemCategoryId)) {
                                    if ($problemCategory->projet_id !== $projet->id) {
                                        $set('problem_category_id', null);
                                    }
                                }
                            }*/
                        })
                        ->reactive(),

                    Forms\Components\Select::make('problem_category_id')
                        ->label(__('Problem Category'))
                        ->options(function (callable $get, callable $set) {
                            /*$projet = Projet::find($get('projet_id'));
                            if ($projet) {
                                return $projet->problemCategories->pluck('name', 'id');
                            }
*/
                            return ProblemCategory::all()->pluck('name', 'id');
                        })
                        ->searchable()
                        ->required(),

                    Forms\Components\TextInput::make('title')
                        ->label(__('Title'))
                        ->required()
                        ->maxLength(255)
                        ->columnSpan([
                            'sm' => 2,
                        ]),

                    Forms\Components\RichEditor::make('description')
                        ->label(__('Description'))
                        ->required()
                        ->maxLength(65535)
                        ->columnSpan([
                            'sm' => 2,
                        ]),
                /*    
                    Forms\Components\Toggle::make('accepter')
                        ->label('Accepter')
                        ->reactive()
                        ->hidden(fn () => !auth()->user()->hasAnyRole(['Super Admin', 'Chef Projet', 'Employeur'])),


                    Forms\Components\Toggle::make('refuser')
                        ->label('Refuser')
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set) => $state ? $set('accepter', false) : null)
                        ->hidden(fn () => !auth()->user()->hasAnyRole(['Super Admin', 'Chef Projet', 'Employeur'])),


                    Forms\Components\Textarea::make('commentaire')
                        ->label('Commentaire')
                        ->visible(fn (callable $get) => $get('refuser')),
*/
                    Forms\Components\Placeholder::make('approved_at')
                        ->translateLabel()
                        ->hiddenOn('create')
                        ->content(fn (?Ticket $record): string => $record->approved_at ? $record->approved_at->diffForHumans() : '-'),

                    Forms\Components\Placeholder::make('solved_at')
                        ->translateLabel()
                        ->hiddenOn('create')
                        ->content(fn (?Ticket $record): string => $record->solved_at ? $record->solved_at->diffForHumans() : '-'),
                ])->columns([
                    'sm' => 2,
                ])->columnSpan(2),

                Card::make()->schema([
                    Forms\Components\Select::make('priority_id')
                        ->label(__('Priority'))
                        ->options(Priority::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    /*Forms\Components\Select::make('statuts_des_tickets_id')
                        ->label(__('Statut'))
                        ->options(StatutDuTicket::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required()
                        ->hiddenOn('create')
                        ->hidden(fn () => !auth()->user()->hasAnyRole(['Super Admin', 'Chef Projet', 'Employeur'])),
*/
                    Forms\Components\Select::make('responsible_id')
                        ->label(__('Responsible'))
                        ->options(
                            User::whereHas('roles', function($query) {
                                $query->whereIn('name', ['Super Admin', 'Chef Projet', 'Employeur']);
                            })->pluck('name', 'id')
                        )
                        ->searchable()
                        ->required()
                        ->hiddenOn('create')
                        ->hidden(fn () => !auth()->user()->hasAnyRole(['Super Admin', 'Chef Projet'])),
                    
                    Forms\Components\Placeholder::make('created_at')
                        ->translateLabel()
                        ->content(fn (?Ticket $record): string => $record ? $record->created_at->diffForHumans() : '-'),

                    Forms\Components\Placeholder::make('updated_at')
                        ->translateLabel()
                        ->content(fn (?Ticket $record): string => $record ? $record->updated_at->diffForHumans() : '-'),
                ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('projet.name')
                    ->searchable()
                    ->label(__('Projet'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('ProblemCategory.name')
                    ->searchable()
                    ->label(__('Catégorie des problèmes'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('owner.name') 
                    ->label(__('User'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('projet.pays.name')
                    ->searchable()
                    ->label(__('Pays'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('statutDuTicket.name')
                    ->label(__('Statut'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->translateLabel()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('projet_id')
                    ->options(Projet::all()->pluck('name', 'id')->toArray())
                    ->label(__('Projet')),
                Tables\Filters\SelectFilter::make('pays_id')
                    ->options(Pays::all()->pluck('name', 'id')->toArray())
                    ->label(__('Pays'))
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            CommentairesRelationManager::class,
        ];
    } 

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'view' => Pages\ViewTicket::route('/{record}'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }

    /**
     * Display tickets based on each role.
     *
     * If it is a Super Admin, then display all tickets.
     * If it is a Admin Projet, then display tickets based on the tickets they have created and their Projet id.
     * If it is a Staff Projet, then display tickets based on the tickets they have created and the tickets assigned to them.
     * If it is a Regular User, then display tickets based on the tickets they have created.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where(function ($query) {
                // Display all tickets to Super Admin
                if (auth()->user()->hasRole('Super Admin')) {
                    return;
                }

                if (auth()->user()->hasRole('Chef Projet')) {
                    $query->where('tickets.projet_id', auth()->user()->projet_id)->orWhere('tickets.owner_id', auth()->id());
                } elseif (auth()->user()->hasRole('Employeur')) {
                    $query->where('tickets.responsible_id', auth()->id())->orWhere('tickets.owner_id', auth()->id());
                } else {
                    $query->where('tickets.owner_id', auth()->id());
                }
            })
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPluralModelLabel(): string
    {
        return __('Tickets');
    }
}

