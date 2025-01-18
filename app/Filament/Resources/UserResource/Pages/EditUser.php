<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\User;
use Filament\Notifications\Notification;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['role_id'] = $this->record->roles->first()?->id;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (User::where('email', $data['email'])->where('id', '!=', $this->record->id)->exists()) {
            Notification::make()
                ->title('Validation Error')
                ->danger()
                ->body('The email is already in use.')
                ->send();

            $this->halt();
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
