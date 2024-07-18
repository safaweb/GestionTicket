<?php

namespace App\Filament\Resources\ProjetResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $title = 'Utilisateurs'; // Added label

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
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
                Tables\Columns\TextColumn::make('name')
                ->label('Nom'),
                Tables\Columns\TagsColumn::make('roles.name')
                ->label('Rôles'),
            ])
            ->filters([
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                ->label('Attacher'),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                ->label('Détacher'),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make()
                ->label('Détacher en masse'),
            ])
        ;
    }
}
