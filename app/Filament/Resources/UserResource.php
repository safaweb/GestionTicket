<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\RolesRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\TicketsRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\SocieteRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\ProjetRelationManager;
use App\Models\Pays;
use App\Models\User;
use Filament\Forms;
use Filament\Tables;
use Carbon\Carbon;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Utilisateurs';
    protected static ?string $navigationGroup = 'Données de base';

    public static function getLabel(): string
    {
        return __('User');
    }
    public static function getPluralLabel(): string
    {
        return __('Users');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->required()->email()
                    ->maxLength(255),
                Forms\Components\Select::make('pays_id')
                    ->label('Pays')
                    ->options(Pays::all()
                    ->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('phone')
                    ->label('Numéro de Téléphone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                        ->label('Actif')
                        ->required(),
                        Forms\Components\DatePicker::make('start_date')
                        ->label('Date Début Du Contrat')
                        ->required()
                        ->default(fn () => Carbon::now())
                        ->hidden(fn ($get) => !$get('is_contrat')), // Use the correct closure parameter
                    
                    Forms\Components\Toggle::make('is_contrat')
                        ->label('Contrat')
                        ->required()
                        ->reactive() // Make the form reactive to changes in this field
                        ->afterStateUpdated(function ($state, $set) {
                            // When the toggle is updated, adjust visibility of the date pickers
                            $set('start_date', $state ? Carbon::now() : null);
                            $set('end_date', $state ? Carbon::now() : null);
                        }),
                    
                    Forms\Components\DatePicker::make('end_date')
                        ->label('Date Fin Du Contrat')
                        ->required()
                        ->default(fn () => Carbon::now())
                        ->hidden(fn ($get) => !$get('is_contrat')), // Use the correct closure parameter
                    ])
        ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nom'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TagsColumn::make('roles.name')->label('Role'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_contrat')
                    ->label('Contart')
                    ->boolean(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->translateLabel()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->translateLabel()
                    ->sortable()
                    ->toggleable(),
            ])
            
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Impersonate::make()
                    ->redirectTo(route('filament.pages.dashboard')),
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
            RolesRelationManager::class,
            SocieteRelationManager::class,
            ProjetRelationManager::class,
            TicketsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
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