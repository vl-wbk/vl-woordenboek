<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * CreateUser provides the user creation interface in the Vlaams Woordenboek administrator panel.
 *
 * This page component handles the registration of new users through a form-based interface.
 * It extends Filament's CreateRecord to provide a standardized creation experience while integrating with the app's notification system for welcoming new users.
 *
 * @property \App\Models\User $record The entity from the created user in the app.
 * @package App\Filament\Resources\UserResource\Pages
 */
final class CreateUser extends CreateRecord
{
    /**
     * Associates this page with the UserResource, enabling proper routing and form generation within the Filament administrator panel.
     * This connection ensures that all user-related operations maintain consistency throughout the app.
     */
    protected static string $resource = UserResource::class;

    /**
     * Handles post-creation tasks after a new user account is created.
     *
     * Filament automatically calls this method after successful user creation.
     * It generates a password reset link that expires in 24 hours and sends a welcome notification to the new user.
     * This ensures users can securely set their initial password through a time-limited process.
     */
    public function afterCreate(): void
    {
        $expiresAt = now()->addDay();

        $this->record->update(['email_verified_at' => now()]);
        $this->record->sendWelcomeNotification($expiresAt);
    }
}
