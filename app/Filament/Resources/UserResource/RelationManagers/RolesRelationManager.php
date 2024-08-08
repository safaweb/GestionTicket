<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Spatie\Permission\Models\Role;

class RolesRelationManager extends RelationManager
{
    protected static string $relationship = 'roles';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('role_id')
                    ->label('Role')
                    ->options(Role::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Role Name'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->form(fn () => [
                        Forms\Components\Select::make('role_id')
                            ->label('')
                            //->options(Role::all()->pluck('name', 'id'))
                            ->options(function() {
                                // Directly fetch the options without caching
                                return Role::pluck('name', 'id');
                            })
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function ($data, $livewire) {
                        $user = $livewire->ownerRecord;
                        $user->roles()->detach();
                        $user->roles()->attach($data['role_id']);
                    }),
            ])
            ->actions([
                DetachAction::make()
                    ->action(function ($record, $livewire) {
                        $user = $livewire->ownerRecord;
                        // Detach the selected role
                        $user->roles()->detach($record->id);
                    }),
            ])
            ->bulkActions([
                DetachBulkAction::make()
                    ->action(function ($records, $livewire) {
                        $user = $livewire->ownerRecord;
                        // Detach all selected roles
                        $user->roles()->detach($records->pluck('id')->toArray());
                    }),
            ]);
    }
}
