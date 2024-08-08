<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatutDuTicketResource\Pages;
use App\Filament\Resources\StatutDuTicketResource\RelationManagers\TicketsRelationManager;
use App\Models\StatutDuTicket;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatutDuTicketResource extends Resource
{
    protected static ?string $model = StatutDuTicket::class;
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#'),
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tickets_count')
                    ->counts('tickets')
                    ->label(__('Tickets Count'))
                    ->sortable(),
            ])
            ->filters([])
            ->actions([])
            ->bulkActions([])
            ->defaultSort('id', 'asc'); // Default sorting by ID
    }

    public static function getRelations(): array
    {
        return [
            TicketsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStatutsDesTickets::route('/'),
            'create' => Pages\CreateStatutDuTicket::route('/create'),
            'view' => Pages\ViewStatutDuTicket::route('/{record}'),
            'edit' => Pages\EditStatutDuTicket::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('tickets') // Eager load tickets count to avoid N+1 problem
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPluralModelLabel(): string
    {
        return __('Statuts Des Tickets');
    }
}
