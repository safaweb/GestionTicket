<?php

namespace App\Filament\Resources\ProjetResource\Pages;

use App\Filament\Resources\ProjetResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;


class CreateProjet extends CreateRecord
{
    protected static string $resource = ProjetResource::class;
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->unique('projets', 'name') // Ensures the 'name' is unique in the 'projets' table
                ->label('Nom du projet '),
            
            // Select::make('pays_id')
            //     ->label('Pays')
            //     ->required()
            //     ->options(Pays::pluck('name', 'id')), // Loads the 'Pays' options for the select field
            
            // Select::make('societe_id')
            //     ->label('Societe')
            //     ->required()
            //     ->options(Societe::pluck('name', 'id')), // Loads the 'Societe' options for the select field
        ];
    }
}
