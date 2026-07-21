<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\{User, VolunteerPosition};
use App\Settings\VolunteerSettings;
use Illuminate\Auth\Access\Response;

/**
 * VolunteerPositionPolicy
 * 
 * This policy governs access control for volunteer recruitment. It implements a dual-layer security model:
 * 
 ** 1) Administrative layer (RBAC): 
 * Standard CRUD methods ('viewAny', 'create', , etc.) check for specific user permissions.
 * This ensures only authorized staff can manage positions within the Flemish dictionary. 
 * 
 ** 2) Business Logic Layer: 
 * The 'apply' method references state-based access. Even if a user is authenticated, the system evaluates global settings 
 * and record-level status to determine if an application can be initiated. 
 * 
 *? Maintainer note: 
 * Permission strings are formatted as '{action}:{resource}'. 
 * The `$permissionPrefixes` array is used by automated discovery tools to map these policies to the database's permission table.
 * 
 * @package App\Policies
 */
final class VolunteerPositionPolicy
{
    /**
     * Permission discovery matric 
     * 
     * Defines the standard actions available for this model. 
     * This list is consumed by automated permission seeding and administrative UI generation.
     * 
     * @var list<string>
     */
    public static array $permissionPrefixes = [
        'viewAny', 'view', 'update', 'delete', 'create'
    ];

    /**
     * Evaluate application eligibility
     * 
     * This method validates if the recruitment windows is currently active for a specific position. 
     * it checks both the instance state ('is_open') and the global configration ('pageRegistrationActive'). 
     * 
     *! SECURITY NOTE:
     *! if access denies, we returns a HTTP 404 (NOT FOUND) rather than a HTTP 403 (FORBIDDEN). 
     *! This prevents external users from identifying the existance of internal or draft positions that are not currently public. 
     *
     * @param  User               $user                 The authenticated user attempting to apply.
     * @param  VolunteerPosition  $volunteerPosition    The specific position being targeted.
     * @return Response                                 Returns an 'allow' response if recruitment is active, otherwise a 'NotFound' denial.
     */
    public function apply(User $user, VolunteerPosition $volunteerPosition): Response
    {
        if ($volunteerPosition->is_open && app(VolunteerSettings::class)->pageRegistrationActive) {
            return Response::allow();
        }

        //! No need for a custom authorization message becasue it is a frontend route
        //! And users have enough with a simple HTTP 404 error message
        return Response::denyAsNotFound();
    }

    /**
     * Authorize index access
     * Determines if the user has the authority to view the collection of positions within the administrative management interface.
     * 
     * @param  User $user  The user requesting access.
     * @return Response    Result of the permission check.
     */
    public function viewAny(User $user): Response
    {
        return ($user->can('view-any:volunteer-position'))
            ? Response::allow()
            : Response::deny(message: 'U hebgt geen toestemming om de lijst met vrijwilligers posities te bekijken.');
    }

    /**
     * Authorize record creation. 
     * Determines if the user is permitted to instantiate new volunteer position in the system/database. 
     *
     * @param  User $user  The user that is requesting access. 
     * @return Response    Result of the permission check.
     */
    public function create(User $user): Response
    {
        return ($user->can('create:volunteer-position'))
            ? Response::allow()
            : Response::deny(message: 'U hebt geen toestemming om een nieuwe vrijwilligers positie aa te maken in het systeem.');
    }

    /**
     * Authorize record modification. 
     * Validates that the user has the 'update' permission for this specific resource. 
     * 
     * @param  User              $user               The authenticated user that is requesting access.
     * @param  VolunteerPosition $volunteerPosition  The position record to be updated. 
     * @return Response                              Result of the permission check
     */
    public function update(User $user, VolunteerPosition $volunteerPosition): Response
    {
        return ($user->can('update:volunteer-position'))
            ? Response::allow()
            : Response::deny(message: 'U hebt geen toestemming om de gegevens van de vrijwilligers positie te wijzigen.');
    }

    /**
     * Authorize invididual record view
     * grants access to view the full administrative details of a single position in the system.
     *
     * @param  User               $user               The authenticated user that is requesting access. 
     * @param  VolunteerPosition  $volunteerPosition  The position record being viewed.
     * @return Response                               Result of the permission check.
     */
    public function view(User $user, VolunteerPosition $volunteerPosition): Response
    {
        return ($user->can('view:volunteer-position'))
            ? Response::allow()
            : Response::deny(message: 'U hebt geen toestemming om de vrijwilliger positie te bekijken.');
    }

    /**
     * Authorize record deletion 
     * Restricts the ability to permanently remove a volunteer position from the system/database. 
     *
     * @param  User               $user               The authenticated user that is requesting access.
     * @param  VolunteerPosition  $volunteerPosition  The position record to be deleted. 
     * @return Response                               Result of the permission check.
     */
    public function delete(User $user, VolunteerPosition $volunteerPosition): Response
    {
        return ($user->can('delete:volunteer-position'))
            ? Response::allow()
            : Response::deny(message: 'U hebt geen toesttemming om de vrijwilligers positie te verwijderen.');
    }
}
