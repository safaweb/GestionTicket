<?php

namespace App\Filament\Resources\ProjetResource\Pages;

use App\Filament\Resources\ProjetResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\TextInput;

class CreateProjet extends CreateRecord
{
    protected static string $resource = ProjetResource::class;
    /*protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->unique('projets', 'name') // Ajout de la règle unique sur la colonne 'nom' de la table 'pays'
                ->label('Nom du projet '),
        ];
    }*/
}
