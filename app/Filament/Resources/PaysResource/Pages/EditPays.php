<?php

namespace App\Filament\Resources\PaysResource\Pages;

use App\Filament\Resources\PaysResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPays extends EditRecord
{
    protected static string $resource = PaysResource::class;
    protected function getActions(): array
    {
        return [
           Actions\ViewAction::make(),
          //  Actions\DeleteAction::make(),
          //  Actions\ForceDeleteAction::make(),
            //Actions\RestoreAction::make(),
        ];
    }
}
