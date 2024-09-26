<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;


class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->visible(fn ($record) => !Auth::user()->hasRole('Super Admin') && !Auth::user()->hasRole(['Chef Projet', 'Collaborateur'])),
            
        ];
    }
    protected function getTitle(): string
    {
        return 'Liste des Tickets'; // Update this line
    }
}
