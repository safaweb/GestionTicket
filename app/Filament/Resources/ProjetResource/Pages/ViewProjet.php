<?php

namespace App\Filament\Resources\ProjetResource\Pages;

use App\Filament\Resources\ProjetResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProjet extends ViewRecord
{
    protected static string $resource = ProjetResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
