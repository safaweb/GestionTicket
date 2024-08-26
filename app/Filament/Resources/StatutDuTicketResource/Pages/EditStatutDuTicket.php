<?php

namespace App\Filament\Resources\StatutDuTicketResource\Pages;

use App\Filament\Resources\StatutDuTicketResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStatutDuTicket extends EditRecord
{
    protected static string $resource = StatutDuTicketResource::class;
    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
           // Actions\DeleteAction::make(),
           // Actions\ForceDeleteAction::make(),
          //  Actions\RestoreAction::make(),
        ];
    }
}
