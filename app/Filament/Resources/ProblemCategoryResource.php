<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProblemCategoryResource\Pages;
use App\Filament\Resources\ProblemCategoryResource\RelationManagers\TicketsRelationManager;
use App\Models\ProblemCategory;
use App\Models\Projet;
use App\Models\User;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ProblemCategoryResource extends Resource
{
    protected static ?string $model = ProblemCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('projet_id')
                    ->label(__('Projets'))
                    ->options(Projet::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('projet.name')
                    ->searchable()
                    ->label(__('Projets')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TicketsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProblemCategories::route('/'),
            'create' => Pages\CreateProblemCategory::route('/create'),
            'view' => Pages\ViewProblemCategory::route('/{record}'),
            'edit' => Pages\EditProblemCategory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])->where(function ($query) {
                if (auth()->user()->hasRole('Admin Projet')) {
                    $query->where('problem_categories.projet_id', auth()->user()->projet_id);
                }
            });
    }

    public static function getPluralModelLabel(): string
    {
        return __('Problem Category');
    }

    public static function createTicket(array $data)
    {
        // Create the ticket
        $ticket = Ticket::create($data);

        // Get the current user
        $currentUser = Auth::user();

        if ($currentUser->hasAnyRole(['Admin Projet', 'Staff Projet', 'Super Admin', 'Client'])) {
            $receiver = User::where('projet_id', $currentUser->projet_id)
                            ->where('id', '!=', $currentUser->id)
                            ->get();
        } else {
            // Send notification to users with specific roles, excluding current user
            $receiver = User::whereHas('roles', function ($q) {
                $q->where('name', 'Admin Projet')
                    ->orWhere('name', 'Staff Projet')
                    ->orWhere('name', 'Super Admin')
                    ->orWhere('name', 'Client');
            })->where('projet_id', $currentUser->projet_id)
              ->where('id', '!=', $currentUser->id)
              ->get();
        }

        // Send the notification to appropriate recipients
        Notification::make()
            ->title('Il y a un nouveau ticket créé ajouté')
            ->actions([
                Action::make('Voir')
                    ->url(route('filament.resources.tickets.view', $ticket->id)),
            ])
            ->sendToDatabase($receiver);
    }
}
