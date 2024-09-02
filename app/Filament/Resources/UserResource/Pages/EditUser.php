<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
          //  Actions\DeleteAction::make(),
         //   Actions\ForceDeleteAction::make(),
          //  Actions\RestoreAction::make(),
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        try {
            // Manually validate the data according to the User model's rules and messages
            validator($data, User::rules($this->record->id), User::messages())->validate();
        } catch (ValidationException $e) {
            // Notify the user of validation errors
            $errorMessage = $this->formatValidationErrors($e->errors());

            Notification::make()
                ->title('Erreur ')
                ->body($errorMessage)
                ->danger()
                ->send();

            // Re-throw the exception to prevent record saving
            throw $e;
        }

        return $data;
    }

    protected function formatValidationErrors(array $errors): string
    {
        // Convert the array of errors to a readable string format
        return collect($errors)
            ->map(function ($error) {
                return implode(' ', $error);
            })
            ->implode("\n");
    }
}
