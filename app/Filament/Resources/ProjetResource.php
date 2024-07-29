<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjetResource\Pages;
use App\Filament\Resources\ProjetResource\RelationManagers\UsersRelationManager;
use App\Models\Projet;
use App\Models\Pays;
use App\Models\Societe;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ProjetResource extends Resource
{
    protected static ?string $model = Projet::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Données de base';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('pays_id')
                    ->label('Pays')
                    ->required()
                    ->options(Pays::all()
                    ->pluck('name', 'id')),
                Forms\Components\Select::make('societe_id')
                    ->label('Societe')
                    ->required()
                    ->options(Societe::all()
                    ->pluck('name', 'id'))
            ])
        ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name') ->label('Nom'),
                Tables\Columns\TextColumn::make('pays.name') 
                ->searchable()
                ->label(__('Pays'))
                ->toggleable(),
                Tables\Columns\TextColumn::make('societe.name') 
                ->searchable()
                ->label(__('Societe'))
                ->toggleable(),
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
            ])
        ;
    }

    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class,
        ];
        
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjets::route('/'),
            'create' => Pages\CreateProjet::route('/create'),
            'view' => Pages\ViewProjet::route('/{record}'),
            'edit' => Pages\EditProjet::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        // Check if the user is a superadmin
    if ($user->hasRole('Super Admin')) {
        // Superadmin can see all projects
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    } else {
        // Other users can see only their associated projects
        return parent::getEloquentQuery()
            ->join('projet_user', 'projets.id', '=', 'projet_user.projet_id')
            ->join('users', 'projet_user.user_id', '=', 'users.id')
            ->where('users.id', $user->id)
            ->select('projets.*', 'users.name as user_name')
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
    }
}
