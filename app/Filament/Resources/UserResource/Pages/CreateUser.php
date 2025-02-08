<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * Runs after the form fiels are saved to the database.
     * This method is a hook just to send the password registration mail to the user for who the account has been created.
     *
     * @return void
     */
    public function afterCreate(): void
    {
        $expiresAt = now()->addDay();
        $this->record->sendWelcomeNotification($expiresAt);
    }
}
