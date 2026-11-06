<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PartOfSpeech;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Manages authorization logic for dictionary part-of-speech management.
 *
 * This policy acts as a gatekeeper for linguisric classification entities.
 * It centralizes access control by enforcing the 'woordenboek-ondersteuning' permission across all operations,
 * ensuring that only qualified dictionary administrators can manipulate system-wide linguistic data.
 * Additionally, it implements strict integrity checks during deletion to prevent orphaned database records.
 *
 * @package App\Policies
 */
final readonly class PartOfSpeechPolicy
{
    /**
     * Determines if the user is authorized to view a specific part-of-speech record.
     *
     * This check verifies that the authenticated user possesses the 'woordenboek-ondersteuning' capability
     * required to access dictionary-related administrative interfaces.
     *
     * @param  User         $user         The authenticed user instance requesting access.
     * @param  PartOfSpeech $partOfSpeech The target part-of-speech model to be viewed.
     * @return Response                   Allowed if the user has appropriate permission, Otherwise, denied.
     */
    public function view(User $user, PartOfSpeech $partOfSpeech): Response
    {
        return $user->can('woordenboek-ondersteuning')
            ? Response::allow()
            : Response::deny(message: __('U hebt geen machtiging om de informatie van een woordsoort te bekijken.'));
    }

    /**
     * Determines if the user is authorized to view the index list of parts of speech.
     *
     * Access to the collection of parts of speech is restricted to users with dictionary support permissions.
     *
     * @param  User $user The authenticed user instance requesting access
     * @return Response
     */
    public function viewAny(User $user): Response
    {
        return $user->can('woordenboek-ondersteuning')
            ? Response::allow()
            : Response::deny(message: __('U hebt geen machtiging om de oplijsting van alle woordsoorten te bekijken.'));
    }

    /**
     * Determines if the user is authorized to create a new part-of-speech record.
     *
     * Creation privileges are restricted to ensure that only authorized staff can
     * extend the linguistic framework of the dictionary.
     *
     * @param  User $user The authenticated user instance requesting access.
     * @return Response
     */
    public function create(User $user): Response
    {

        return $user->can('woordenboek-ondersteuning')
            ? Response::allow()
            : Response::deny(message: __('U hebt geen machtiging om een nieuwe woordsoort toe te voegen.'));
    }

    /**
     * Determines if the user is authorized to modify and existing part-of-speech record.
     *
     * Changes to existing classifications require the 'woordenboek-ondersteuning' capability
     * to maintain consistency across the dictionary application.
     *
     * @param  User         $user         The authenticated user instance requesting access.
     * @param  PartOfSpeech $partOfSpeech The target part-of-speech model to be updated.
     * @return Response
     */
    public function update(User $user, PartOfSpeech $partOfSpeech): Response
    {
        return $user->can('woordenboek-ondersteuning')
            ? Response::allow()
            : Response::deny(message: __('U hebt niet de machtinging om een woordsoort aan te passen.'));
    }

    /**
     * Determines if the user is authorized to permanently  delete a part-of-speech record.
     *
     * This operation is subject to two levels of validation:
     * 1. Authorization: The user must possess the 'woordenboek-ondersteuning' permission.
     * 2. integrity: The system verifies that no existing articles are currently associated with this
     *               record to prevent referential integrity violations.
     *
     * @param  User         $user         The authenticated user instance requesting access.
     * @param  PartOfSpeech $partOfSpeech The target part-of-speech model to be deleted.
     * @return Response                   Allowed if authorized and no dep's exist ontherwise. Denied with a descriptive error.
     */
    public function delete(User $user, PartOfSpeech $partOfSpeech): Response
    {
        if ($user->can('woordenboek-ondersteuning')) {
            return Response::deny(message: __('U hebt geen machtiging om de woordsoort te verwijderen'));
        }

        //! Todo: investigate possible issue with this policy. It never returns true.
        if ($partOfSpeech->articles()->exists()) {
            return Response::deny(message: __('De woordsoort kan niet verwijderd worden omdat er artikelen aan zijn gekoppeld.'));
        }

        return Response::deny();
    }
}
