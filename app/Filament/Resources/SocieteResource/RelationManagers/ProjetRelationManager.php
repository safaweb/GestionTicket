<?php

namespace App\Filament\Resources\SocieteResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use App\Models\Pays;


class ProjetRelationManager extends RelationManager
{
    protected static string $relationship = 'Projet';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $title = 'Projets';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\Select::make('pays_id')
                    ->label('Pays')
                    ->required()
                    ->options(Pays::all()
                    ->pluck('name', 'id')),
            ])
        ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('pays.name') 
                ->searchable()
                ->label(__('Pays'))
                ->toggleable(),
            ])
            ->filters([
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
        ;
    }
}
