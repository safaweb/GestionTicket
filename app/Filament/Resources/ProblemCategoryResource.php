<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProblemCategoryResource\Pages;
use App\Filament\Resources\ProblemCategoryResource\RelationManagers\TicketsRelationManager;
use App\Models\ProblemCategory;

use App\Models\User;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ProblemCategoryResource extends Resource
{
    protected static ?string $model = ProblemCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?int $navigationSort = 5;

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
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
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
            ]);
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
            'index' => Pages\ListProblemCategories::route('/'),
            'create' => Pages\CreateProblemCategory::route('/create'),
            'view' => Pages\ViewProblemCategory::route('/{record}'),
            'edit' => Pages\EditProblemCategory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPluralModelLabel(): string
    {
        return __('Catégories Des Problèmes');
    }
}

  /*public static function getEloquentQuery(): Builder in return parent::getEloquentQuery()
    ->where(function ($query) {
        if (auth()->user()->hasRole('Chef Projet')) {
            $query->where('problem_categories.projet_id', auth()->user()->projet_id);
        }
    });*/