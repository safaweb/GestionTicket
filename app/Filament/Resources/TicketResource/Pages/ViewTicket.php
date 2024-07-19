<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewTicket extends ViewRecord
{
    protected static string $resource = TicketResource::class;
    protected function getActions(): array
    {
        return [
            Actions\EditAction::make()    
            ->visible(fn ($record) => Auth::user()->hasAnyRole(['Super Admin', 'Chef Projet', 'Employeur'])
            && $record->validation_id === 1) // Only visible if validation_id is 1
        ];
    }
}
