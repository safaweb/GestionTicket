<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use App\Models\Societe; // Make sure to import the Societe model

class SocieteRelationManager extends RelationManager
{
    protected static string $relationship = 'societes';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $title = 'Societes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->form(fn ($record) => [
                        Forms\Components\Select::make('societe_id')
                            ->label('')
                            ->options(Societe::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ]),
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
