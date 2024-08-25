<?php

namespace App\Filament\Resources\PaysResource\Pages;

use App\Filament\Resources\PaysResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\TextInput;

class CreatePays extends CreateRecord
{
    protected static string $resource = PaysResource::class;

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->unique('pays', 'name') // Ajout de la règle unique sur la colonne 'nom' de la table 'pays'
                ->label('Nom du Pays'),
        ];
    }
}
