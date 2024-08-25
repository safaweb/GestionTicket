<?php

namespace App\Filament\Resources\SocieteResource\Pages;

use App\Filament\Resources\SocieteResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\TextInput;

class CreateSociete extends CreateRecord
{
    protected static string $resource = SocieteResource::class;
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->unique('societes', 'name') // Ajout de la règle unique sur la colonne 'nom' de la table 'pays'
                ->label('Nom du societe '),
        ];
    }

}
