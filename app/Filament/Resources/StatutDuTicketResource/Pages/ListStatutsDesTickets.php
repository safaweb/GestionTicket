<?php

namespace App\Filament\Resources\StatutDuTicketResource\Pages;

use App\Filament\Resources\StatutDuTicketResource;
use App\Filament\Widgets\StatutsDesTicketsChart;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatutsDesTickets extends ListRecords
{
    protected static string $resource = StatutDuTicketResource::class;
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatutsDesTicketsChart::class,
        ];
    }

    protected function getHeaderWidgetsColumns(): int | array
    {
        return 1;
    }
}
