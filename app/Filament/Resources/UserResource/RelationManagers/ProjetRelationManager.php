<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use App\Models\Projet;
use App\Models\Pays;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Notifications\Notification;

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
                    ->options(function() {
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
                            ->options(function() {
                                return Projet::pluck('name', 'id');
                            })
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function ($data, $livewire) {
                        $user = $livewire->ownerRecord;
                        $projetId = $data['projet_id'];

                        // Check if the project is already attached
                        if ($user->projets()->where('projet_id', $projetId)->exists()) {
                            Notification::make()
                                ->title('Erreur')
                                ->body('Ce projet est déja attaché.')
                                ->danger()
                                ->send();
                        } else {
                            $user->projets()->attach($projetId);
                        }
                    }),
            ])
            ->actions([
                DetachAction::make()
                    ->action(function ($record, $livewire) {
                        $user = $livewire->ownerRecord;
                        // Detach the selected project
                        $user->projets()->detach($record->id);
                    }),
            ])
            ->bulkActions([
                DetachBulkAction::make()
                    ->action(function ($records, $livewire) {
                        $user = $livewire->ownerRecord;
                        // Detach all selected projects
                        $user->projets()->detach($records->pluck('id')->toArray());
                    }),
            ]);
    }
}
