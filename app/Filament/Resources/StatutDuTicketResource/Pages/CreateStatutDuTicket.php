<?php

namespace App\Filament\Resources\StatutDuTicketResource\Pages;

use App\Filament\Resources\StatutDuTicketResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\TextInput;
class CreateStatutDuTicket extends CreateRecord
{
    protected static string $resource = StatutDuTicketResource::class;
}
