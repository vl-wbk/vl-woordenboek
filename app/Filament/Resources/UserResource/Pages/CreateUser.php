<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/**
 * @property \App\Models\User $record The user model related to the created user entoty in the Filament backend
 */
final class CreateUser extends CreateRecord
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
