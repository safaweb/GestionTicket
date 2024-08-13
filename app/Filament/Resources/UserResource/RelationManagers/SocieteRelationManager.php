<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use App\Models\Societe;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;

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
                    ->form(function ($record) {
                        return [
                            Forms\Components\Select::make('societe_id')
                                ->label('')
                                //->options(Societe::all()->pluck('name', 'id'))
                                ->options(function() {
                                    // Directly fetch the options without caching
                                    return Societe::pluck('name', 'id');
                                })
                                ->searchable()
                                ->required(),
                        ];
                    })
                    ->action(function ($data, $livewire) {
                        $user = $livewire->ownerRecord;
                        $user->societes()->detach();
                        $user->societes()->attach($data['societe_id']);
                       
        
                    }),
                
            ])
            ->actions([
                DetachAction::make()
                    ->action(function ($record, $livewire) {
                        $user = $livewire->ownerRecord;
                        // Detach the selected project
                        $user->societes()->detach($record->id);
                    }),
            ])
            ->bulkActions([
                DetachBulkAction::make()
                    ->action(function ($records, $livewire) {
                        $user = $livewire->ownerRecord;
                        // Detach all selected projects
                        $user->societes()->detach($records->pluck('id')->toArray());
                    }),
            ]);
    }
}
