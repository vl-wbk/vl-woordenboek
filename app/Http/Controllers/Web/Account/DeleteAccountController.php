<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Account;

use App\Actions\Account\DeleteAccount;
use App\Http\Requests\Account\DeleteAccountRequest;
use Illuminate\Http\RedirectResponse;
use Spatie\RouteAttributes\Attributes\Post;

/**
 * DeleteAccountController handles the user account deletion requests.
 *
 * This single-action controller processes requests from users wanting to delete their accounts from the Vlaams Woordenboek platform.
 * It uses route attributes for configuration and enforces authentication through middleware to ensure only logged-in users can delete their accounts.
 *
 * @package App\Http\Controllers\Web\Account
 */
final readonly class DeleteAccountController
{
    /**
     * Processes the account deletion rquest.
     *
     * This invokable method handles the POST request to delete a user account.
     * It delegates the actual deletion process to a dedicated action class and redirects to the homepage after successful deletion.
     * The method is protected by authentication middleware and validates the request through a form request class.
     *
     * @param  DeleteAccountRequest  $deleteAccountRequest  The validated request data
     * @param  DeleteAccount         $deleteAccount         The account deletion action
     * @return RedirectResponse                             Redirects to the homepage after deletion.
     */
    #[Post(uri: '/account-verwijderen', name: 'account.delete', middleware: ['auth'])]
    public function __invoke(DeleteAccountRequest $deleteAccountRequest, DeleteAccount $deleteAccount): RedirectResponse
    {
        $deleteAccount($deleteAccountRequest);

        return redirect('/');
    }
}
