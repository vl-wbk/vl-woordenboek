<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Filament\Actions\Exports\Models\Export;

/**
 * Defines the authorization rules for `Export` models.
 *
 * This policy ensures that users can only interact with the exports that they have personally created.
 * This is a crucial security measure to prevent unauthorized access to other users' data exports within the Filament panel.
 *
 * @package App\Policies
 */
final readonly class ExportPolicy
{
    /**
     * Determines whether the given user can view the specified export.
     *
     * The policy grants permission only if the authenticated user is the owner of the export.
     * It performs this check by comparing the user who initiated the export with the currently authenticated user.
     *
     * @param  User   $user    The authenticated user attempting to view the export.
     * @param  Export $export  The export model instance being checked.
     * @return bool            Returns `true` if the user is the owner of the export, otherwise `false`.
     */
    public function view(User $user, Export $export): bool
    {
        return $export->user()->is($user);
    }
}
