<?php

declare(strict_types=1);

namespace App\Policies;

use Exception;
use App\Models\User;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Auth\Access\Response;

/**
 * Defines the authorization rules for `Export` models.
 *
 * This policy ensures that users can only interact with the exports that they have personally created.
 * This is a crucial security measure to prevent unauthorized access to other users' data exports within the Filament panel.
 *
 * @link file://tests/Unit/Authorization/BlogPolicyTest.php
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
     * @return Response        Returns `true` if the user is the owner of the export, otherwise `false`.
     *
     * @throws Exception
     */
    public function view(User $user, Export $export): Response
    {
        return ($export->user()->is($user))
			? Response::allow()
			: Response::deny();
    }
	
    public function create(User $user): Response
    {
        return $user->can('export_article')
			? Response::allow()
			: Response::deny();
    }
}
