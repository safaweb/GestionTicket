<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getActions(): array
    {

        Notification::make()
        ->title('Il y a un nouveau ticket créé')
        ->actions([
            Action::make('Voir')
                ->url(route('filament.resources.tickets.view', $ticket->id)),
        ])
        ->sendToDatabase($receiver);
        
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
