<?php

namespace App\Filament\Resources\ProblemCategoryResource\Pages;

use App\Filament\Resources\ProblemCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProblemCategories extends ListRecords
{
    protected static string $resource = ProblemCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    
    protected function getTitle(): string
    {
        return 'Liste des Catégories des problèmes'; // Update this line
    }
}
