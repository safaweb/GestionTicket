<?php

namespace App\Filament\Resources\ProblemCategoryResource\Pages;

use App\Filament\Resources\ProblemCategoryResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\TextInput;

class CreateProblemCategory extends CreateRecord
{
    protected static string $resource = ProblemCategoryResource::class;
    protected function getTitle(): string
    {
        return 'Créer Catégorie de problème';
    }
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->unique('problem_categories', 'name') // Ajout de la règle unique sur la colonne 'nom' de la table 'pays'
                ->label('Nom du catégorie du problème '),
        ];
    }
}

