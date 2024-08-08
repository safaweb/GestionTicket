<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use App\Models\Projet; // Ensure the Projet model is imported
use App\Models\Pays;
use Illuminate\Support\Facades\Cache;

class ProjetRelationManager extends RelationManager
{
    protected static string $relationship = 'projets';
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
                    //->options(Pays::all()->pluck('name', 'id')),
                    ->options(function() {
                        // Using a cache method for options
                        return Pays::cacheFor(now()->addMinutes(10))->pluck('name', 'id');
                    }),
            ]);
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
            ->filters([])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->form(fn () => [
                        Forms\Components\Select::make('projet_id')
                            ->label('')
                            //->options(Projet::all()->pluck('name', 'id'))
                            /*->options(function() {
                                // Cache the options for 10 minutes
                                return Cache::remember('projet_options', now()->addMinutes(10), function () {
                                    return Projet::pluck('name', 'id');
                                });
                            })*/
                            ->options(function() {
                                // Directly fetch the options without caching
                                return Projet::pluck('name', 'id');
                            })
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function ($data, $livewire) {
                        $livewire->ownerRecord->projets()->attach($data['projet_id']);
                    }),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
