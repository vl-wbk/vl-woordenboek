<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Handles authorization logic for Passkey-related operations.
 *
 * This policy governs access control for the management of passkeys,
 * ensuring that only authorized users with sufficient privileges can perform administrative actions
 * such as deletion.
 *
 * @package App\Policies
 */
final readonly class PasskeyPolicy
{
    /**
     * Determines if the authenticed user has authorization to delete a passkey.
     *
     * Access is restricted to users holding the developer role.
     * This is a privileged administrative action that bypasses standard ownership checks.
     *
     * @param  User $user The authenticated user performing the request.
     * @return Response
     */
    public function delete(User $user): Response
    {
        return $user->isDeveloper() ? Response::allow() : Response::deny();
    }
}
