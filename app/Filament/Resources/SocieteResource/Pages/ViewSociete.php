<?php

namespace App\Filament\Resources\SocieteResource\Pages;

use App\Filament\Resources\SocieteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSociete extends ViewRecord
{
    protected static string $resource = SocieteResource::class;
    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
