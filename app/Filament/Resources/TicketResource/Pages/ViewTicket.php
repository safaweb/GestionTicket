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
            ->visible(fn ($record) => Auth::user()->hasAnyRole(['Super Admin', 'Chef Projet', 'Employeur']) && in_array($record->validation_id, [4, 1])),
              Actions\DeleteAction::make()
              ->visible(fn ($record) => Auth::user()->hasAnyRole(['Super Admin', 'Chef Projet', 'Employeur']) && in_array($record->validation_id, [4, 1])),
        ];

            return [
                Action::make('download_attachment')
                    ->label('Télécharger l\'attachement')
                    ->icon('heroicon-o-download')
                    ->action(function () {
                        $record = $this->record; // Récupère le ticket en cours de visualisation
                        return response()->download(storage_path('app/public/' . $record->attachments));
                    })
                    ->visible(fn () => !empty($this->record->attachments)),
            ];
        
    }

   
}
