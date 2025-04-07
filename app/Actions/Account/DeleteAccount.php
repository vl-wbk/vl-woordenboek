<?php

declare(strict_types=1);

namespace App\Actions\Account;

use App\Http\Requests\Account\DeleteAccountRequest;
use Illuminate\Support\Facades\Auth;

/**
 * DeleteAccount handles the complete account removal process for users.
 *
 * This action class orchestrates the secure deletion of a user account, including proper session cleanup and authentication state management.
 * It executes all necessary steps in the correct order to ensure no orphaned data or session artifacts remain.
 */
final readonly class DeleteAccount
{
    /**
     * Executes the deletion process.
     *
     * The method begins by retrievinbg the authenticated user from the request, then preceeds to log them out of their current session.
     * After logout, it permanently removes the user record from the database.
     * Finally, it performs the security cleanup by invalidating the session and regenerating the CSRF token to prevent any potential security vulnerabilities.
     *
     * @param  DeleteAccountRequest  $deleteAccountRequest  The validated delection request.
     */
    public function __invoke(DeleteAccountRequest $deleteAccountRequest): void
    {
        $user = $deleteAccountRequest->user();

        Auth::logout();

        $user->delete();

        $deleteAccountRequest->session()->invalidate();
        $deleteAccountRequest->session()->regenerateToken();
    }
}
