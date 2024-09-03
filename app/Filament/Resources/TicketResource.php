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
use App\Models\Validation;
use App\Models\User;
use App\Models\Qualification;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Auth;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Forms\Components\View;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component as Livewire;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketExport;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?int $navigationSort = 3;
    protected static ?string $recordTitleAttribute = 'title';
    public static ?bool $responsable = false;
    public static function getPluralModelLabel(): string
    {
        return __('Tickets');
    }
    public static function form(Form $form): Form
    {
       if(Str::contains(Route::currentRouteName(), 'tickets.create'))
       {
        $userId = Filament::auth()->user()->id; // Get the current user's ID
        $projetIds = DB::select("select societe_id from societe_user where user_id = ?", [$userId]);                       
        $societeIdsArray = array_map(function($value) { return $value->societe_id;}, $projetIds);  
        $project= Projet::whereIn('societe_id', $societeIdsArray)
            ->pluck('name', 'id')
            ->toArray();
       }
       else{
        $project= Projet::query()
        ->pluck('name', 'id')
        ->toArray();
    }

        $user= AUTH::user();
        return $form
            ->schema([
                Card::make()->schema([
                    Forms\Components\Select::make('qualification_id')
                        ->label(__('Qualifications'))
                        ->required()
                        //->options(Qualification::all()->pluck('name', 'id'))
                        ->options(Qualification::query()->pluck('name', 'id')->toArray()) // Optimize options loading
                        ->searchable()
                        ->disabled(fn ($record) => $record !== null ),
                    Forms\Components\Select::make('projet_id')
                        ->label(__('Projets'))
                        //->options(Projet::query()->pluck('name', 'id')->toArray()) // Optimize options loading              
                        ->options($project)                         
                        // Optimize options loading
                        ->searchable()
                        ->required()
                        ->reactive()
                        ->disabled(fn ($record) => $record !== null),
                    Forms\Components\Select::make('problem_category_id')
                        ->label(__('Problem Category'))
                        ->options(function (callable $get, callable $set) {return ProblemCategory::all()->pluck('name', 'id');})
                        ->searchable()
                        ->required()
                        ->disabled(fn ($record) => $record !== null),
                    Forms\Components\Select::make('priority_id')
                        ->label(__('Priority'))
                        //->options(Priority::all()->pluck('name', 'id'))
                        ->options(Priority::query()->pluck('name', 'id')->toArray()) // Optimize options loading
                        ->searchable()
                        ->required()
                        ->disabled(fn ($record) => $record !== null),
                    Forms\Components\TextInput::make('title')
                        ->label(__('Title'))
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(['sm' => 2,])
                        ->disabled(fn ($record) => $record !== null),
                        
                        Forms\Components\RichEditor::make('description')
                            ->label(__('Description'))
                            ->required()
                            ->maxLength(65535)
                            ->columnSpan(['sm' => 2])
                            ->disabled(fn ($record) => $record !== null)
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
                            ])
                            ->extraAttributes([ 'style' => 'max-height: 300px; overflow-y: auto; word-wrap: break-word;', ]),

                            Forms\Components\FileUpload::make('attachments')
                            ->directory('tickets-attachements/' . date('m-y'))
                            ->maxSize(20000) // La taille est en Ko, donc 20000 Ko = 20 Mo
                           ->enableDownload()
                            ->columnSpan(['sm' => 2]),
                             
                    Forms\Components\Placeholder::make('approved_at')
                        ->label('Validée le:')
                        ->hiddenOn('create')
                        ->content(fn (?Ticket $record): string => $record && $record->approved_at ? $record->approved_at->format('d-m-Y : H:i') : '-')
                        ->disabled(fn ($record) => $record !== null),
                    Forms\Components\Placeholder::make('solved_at')
                        ->translateLabel()
                        ->hiddenOn('create')
                        ->content(fn (?Ticket $record): string => $record->solved_at ? $record->solved_at->format('d-m-Y : H:i') : '-')
                        ->disabled(fn ($record) => $record !== null),
                    ])->columns(['sm' => 2,
                    ])->columnSpan(2),
                Card::make()->schema([
                   
                    Forms\Components\Placeholder::make('statuts_des_tickets_id')
                        ->label(__('Statut'))
                        ->hiddenOn('create')
                        ->content(fn (?Ticket $record): string => $record->statutDuTicket ? $record->statutDuTicket->name : '-')
                        ->hidden(fn () => !auth()->user()->hasAnyRole(['Super Admin', 'Chef Projet', 'Employeur'])),
                    Forms\Components\Select::make('responsible_id')
                        ->label(__('Responsible'))
                        ->options(
                            User::query()->whereHas('roles', function($query) {
                                $query->whereIn('name', ['Chef Projet', 'Employeur']);
                            })->pluck('name', 'id')->toArray() // Optimize options loading
                        )
                        ->searchable()
                        ->required()
                        ->hiddenOn('create')
                        ->hidden(fn () => !auth()->user()->hasAnyRole(['Super Admin', 'Chef Projet', 'Employeur']))
                        ->reactive()
                        ->afterStateUpdated(function (callable $set, $state) {
                        if ($state) {  
                            $responsable=true;
                        }
                    }),
                    Forms\Components\Placeholder::make('created_at')
                        ->translateLabel()
                        ->content(fn (?Ticket $record): string => $record ? $record->created_at->format('d-m-Y : H:i') : '-')
                        ->disabled(fn ($record) => $record !== null),
                    Forms\Components\Placeholder::make('updated_at')
                        ->translateLabel()
                        ->content(fn (?Ticket $record): string => $record ? $record->updated_at->format('d-m-Y : H:i') : '-')
                        ->disabled(fn ($record) => $record !== null),
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
                Tables\Columns\TextColumn::make('owner.name') 
                    ->label(__('User'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('responsible.name') 
                    ->label(__('Responsible'))
                    ->sortable()
                    ->searchable()
                    ->hidden(fn () => !auth()->user()->hasAnyRole(['Super Admin', 'Chef Projet','Employeur']))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('projet.pays.name')
                    ->searchable()
                    ->label(__('Pays'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('validation.name')
                    ->label(__('Validation'))
                    ->extraAttributes(function ($record) {
                        // Colorize the text based on validation status
                        if ($record->validation) {
                            // Colorize the text based on validation status
                            if ($record->validation->name === 'Accepter') {
                                return ['style' => 'color: #666699'];
                            } elseif ($record->validation->name === 'Terminer') {
                                return ['style' => 'color: #ffbf00'];
                            } elseif ($record->validation->name === 'Refuser') {
                                return ['style' => 'color: #ff6666'];}}return [];})
                    ->sortable(),  
                Tables\Columns\TextColumn::make('statutDuTicket.name')
                    ->label(__('Statut'))
                    ->sortable()
                    ->extraAttributes(function ($record) {
                        // Colorize the text based on status
                        if ($record->statutDuTicket->name === 'Résolu') {
                            return ['style' => 'color: green;'];
                        } elseif ($record->statutDuTicket->name === 'Ouvert') {
                            return ['style' => 'color: blue;'];
                        } elseif ($record->statutDuTicket->name === 'En Cours') {
                            return ['style' => 'color: yellow;'];
                        } elseif ($record->statutDuTicket->name === 'Non Résolu') {
                        return ['style' => 'color: red;'];}
                        return [];}),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->translateLabel()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('validation_id')
                    ->options(Validation::query()->pluck('name', 'id')->toArray()) // Optimize filter loading
                    ->label(__('Validation')),
                Tables\Filters\SelectFilter::make('tatuts_des_tickets_id')
                    ->options(StatutDuTicket::query()->pluck('name', 'id')->toArray()) // Optimize filter loading
                    ->label(__('Statut Du Ticket')),
                Tables\Filters\SelectFilter::make('projet_id')
                ->options(function () {
                    $user = Filament::auth()->user(); // Get the current user
            
                    return Projet::where('societe_id', function ($query) use ($user) {
                        $query->select('projet_id')
                              ->from('projet_user')
                              ->where('user_id', $user->id)
                              ->limit(1);
                    })
                    ->pluck('name', 'id') // Pluck project names and IDs
                    ->toArray();
                })   // Optimize filter loading
                    ->label(__('Projet')),
                //Tables\Filters\TrashedFilter::make()
            ])
            ->actions([
                Tables\Actions\Action::make('attachment')->action(function ($record) {
                    return response()->download('storage/' . $record->attachments);
                })->hidden(fn ($record) => $record->attachments == ''),
                Tables\Actions\ViewAction::make()
                ->label('')
                ->icon('heroicon-s-eye'),
                Tables\Actions\EditAction::make()
                ->visible(fn ($record) => Auth::user()->hasAnyRole(['Super Admin', 'Chef Projet', 'Employeur']) && in_array($record->validation_id, [4, 1]))
                ->label('')
                ->icon('heroicon-s-pencil'),])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                //Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label(__('Export to Excel'))
                    ->icon('heroicon-o-download')
                    ->action(function () {
                        return Excel::download(new TicketExport, 'table_Ticket.xlsx');
                    }),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['projet', 'owner', 'responsible', 'validation', 'statutDuTicket']) // Eager load relationships
            ->where(function ($query) {
                // Display all tickets to Super Admin
                if (auth()->user()->hasRole('Super Admin')) {
                    return;
                }
                $user = auth()->user();
                if ($user->hasRole('Chef Projet')) {
                    $query->where(function ($q) use ($user) {
                        $q->whereIn('tickets.projet_id', function ($subQuery) use ($user) {
                            $subQuery->select('projet_user.projet_id') // Adjusted column name
                                    ->from('projet_user')
                                    ->where('projet_user.user_id', $user->id);
                        })->orWhere('tickets.owner_id', $user->id);
                    });
                } elseif (auth()->user()->hasRole('Employeur')) {
                    $query->where('tickets.responsible_id', auth()->id())->orWhere('tickets.owner_id', auth()->id());
                } else {
                    $query->where('tickets.owner_id', auth()->id());
                }
            })
          ;
    }
}