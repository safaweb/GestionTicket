<?php

namespace App\Filament\Resources\StatutDuTicketResource\Pages;

use App\Filament\Resources\StatutDuTicketResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStatutDuTicket extends ViewRecord
{
    protected static string $resource = StatutDuTicketResource::class;
    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
