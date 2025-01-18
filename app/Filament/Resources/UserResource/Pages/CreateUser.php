<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Filament\Notifications\Notification;


class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): User
    {
        $user = static::getModel()::create($data);

        if (isset($data['role_id'])) {
            $role = Role::find($data['role_id']);
            if ($role) {
                $user->assignRole($role);
            }
        }

        return $user;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        
        if (User::where('email', $data['email'])->exists()) {
            \Filament\Notifications\Notification::make()
                ->title('Error')
                ->danger()
                ->body('This email is already in use.')
                ->send();
    
            $this->halt();
        }
    
        return $data;
    }
    


    
}
