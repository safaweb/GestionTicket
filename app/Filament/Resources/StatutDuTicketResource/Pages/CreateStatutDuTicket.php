<?php

namespace App\Filament\Resources\StatutDuTicketResource\Pages;

use App\Filament\Resources\StatutDuTicketResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\TextInput;
class CreateStatutDuTicket extends CreateRecord
{
    protected static string $resource = StatutDuTicketResource::class;
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->unique('statuts_des_tickets', 'name') // Ajout de la règle unique sur la colonne 'nom' de la table 'pays'
                ->label('Nom du statut du ticket'),
        ];
    }
}
