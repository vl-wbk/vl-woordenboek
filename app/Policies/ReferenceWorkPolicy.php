<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ReferenceWork;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Class ReferenceWorkPolicy
 *
 * This class serves as the central authority for authorizing actions against ReferenceWork models.
 *
 * ARCHITECTURE NOTE:
 * As of the current implementation, access control is monolithic. Every action is gated by
 * the 'woordenboek-ondersteuning' permission. If the business requirements evolve to
 * distinguish between 'viewers' and 'editors', this policy should be the primary
 * location for those logic changes.
 *
 * @package App\Policies
 */
final readonly class ReferenceWorkPolicy
{
    /**
     * View authorization
     *
     * Evaluates if a user has sufficient privileges to view the details of a single  ReferenceWork instance.
     * This is required for showing individual dictionary entries or metadata in the administrative interface.
     *
     * @param  User          $user          The identity attempting to view the record.
     * @param  ReferenceWork $referenceWork The specific entity being requested.
     * @return Response                     Allow if permitted, otherwise a Dutch denial message.
     */
    public function view(User $user, ReferenceWork $referenceWork): Response
    {
        return $user->can("woordenboek-ondersteuning")
            ? Response::allow()
            : Response::deny(message: "U heeft geen toestemming om dit naslagwerk te bekijken.");
    }

    /**
     * Index authorization
     *
     * Controls access to the primary listing of all reference works.
     * This check is typically performed before the collection is fetched from the database to prevent unauthorized
     * information disclosure at the list level.
     *
     * @param  User $user The identity attempting to access the list.
     * @return Response   Allow if permitted, otherwise display a dutch denial message.
     */
    public function viewAny(User $user): Response
    {
        return $user->can("woordenboek-ondersteuning")
            ? Response::allow()
            : Response::deny(message: "u geeft geen toestemming om de lijst met naslagwerken te bekijken.");
    }

    /**
     * Creation authorization
     *
     * Validates the user's right to instantiate new reference work records.
     * Only users with expliciet dictionary support permissions are permitted to expand the library.
     *
     * @param  User $user The identity attempting to create a record.
     * @return Response
     */
    public function create(User $user): Response
    {
        return $user->can("woordenboek-ondersteuning")
            ? Response::allow()
            : Response::deny(message: "U heeft geen toestemmintg om nieuwe naslagwerken aan te maken.");
    }

    /**
     * Update authorization
     *
     * Ensures that only authorized staff can modify existing reference work records.
     * This protects the integrity of the dictionart data by preventing unauthorized edits.
     *
     * @param  User          $user          The identity attempting the modification.
     * @param  ReferenceWork $referenceWork The specific entity to be modified.
     * @return Response                     Allow if permitted, otherwise display a dutch denial message.
     */
    public function update(User $user, ReferenceWork $referenceWork): Response
    {
        return $user->can("woordenboek-ondersteuning")
            ? Response::allow()
            : Response::deny(message: "U heeft geen toestemming om naslagwerken te bewerken.");
    }

    /**
     * Delete authorization
     *
     * The most restrictive action. This governs the permanent removal of records from the system.
     * Currently, this shares the samen permission level as 'view', 'update' and 'create'.
     *
     * @param  User          $user          The identity attempting the deletion.
     * @param  ReferenceWork $referenceWork The specific entity to be deleted.
     * @return Response                     Allow if permitted, otherwise a display a dutch denial message.
     */
    public function delete(User $user, ReferenceWork $referenceWork): Response
    {
        return $user->can("woordenboek-ondersteuning")
            ? Response::allow()
            : Response::deny(message: "U heeft geen toestemming om naslagwerken te verwijderen.");
    }
}
