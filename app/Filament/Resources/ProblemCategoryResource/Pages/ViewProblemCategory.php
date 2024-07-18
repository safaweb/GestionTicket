<?php

namespace App\Filament\Resources\ProblemCategoryResource\Pages;

use App\Filament\Resources\ProblemCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProblemCategory extends ViewRecord
{
    protected static string $resource = ProblemCategoryResource::class;
    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
    protected function getTitle(): string
    {
        return 'Afficher Catégorie de problème'; // Update this line
    } 
}
