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
}

