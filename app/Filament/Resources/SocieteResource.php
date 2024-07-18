<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocieteResource\Pages;
use App\Filament\Resources\SocieteResource\RelationManagers\ProjetRelationManager;
//use App\Filament\Resources\SocieteResource\RelationManagers\UsersRelationManager;
use App\Models\Societe;
use App\Models\Projet;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SocieteResource extends Resource
{
    protected static ?string $model = Societe::class;
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
            ])
        ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name') ->label('Nom'),
                //Tables\Columns\TextColumn::make('projet.name') 
                //->searchable()
                //->label(__('Projet'))
               // ->toggleable(),
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
            ProjetRelationManager::class,
        ];
        
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSocietes::route('/'),
            'create' => Pages\CreateSociete::route('/create'),
            'view' => Pages\ViewSociete::route('/{record}'),
            'edit' => Pages\EditSociete::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
        ;
    }
}
