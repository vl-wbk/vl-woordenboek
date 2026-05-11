<?php

declare(strict_types=1);

namespace App\Policies;

use App\Attributes\Todo;
use App\Models\User;
use App\Models\VolunteerPosition;
use App\Settings\VolunteerSettings;
use Illuminate\Auth\Access\Response;

#[Todo(message: 'Write docblocks for the class and his methods', priority: 'high')]
final class VolunteerPositionPolicy
{
    /**
     * @var list<string>
     */
    public static array $permissionPrefixes = [
        'viewAny', 'view', 'update', 'delete', 'create'
    ];

    public function apply(User $user, VolunteerPosition $volunteerPosition): Response
    {
        if ($volunteerPosition->is_open && app(VolunteerSettings::class)->pageRegistrationActive) {
            return Response::allow();
        }

        //! No need for a custom authorization message becasue it is a frontend route
        //! And users have enough with a simple HTTP 404 error message
        return Response::denyAsNotFound();
    }

    public function viewAny(User $user): Response
    {
        return ($user->can('view-any:volunteer-position'))
            ? Response::allow()
            : Response::deny(message: 'U hebgt geen toestemming om de lijst met vrijwilligers posities te bekijken.');
    }

    public function create(User $user): Response
    {
        return ($user->can('create:volunteer-position'))
            ? Response::allow()
            : Response::deny(message: 'U hebt geen toestemming om een nieuwe vrijwilligers positie aa te maken in het systeem.');
    }

    public function update(User $user, VolunteerPosition $volunteerPosition): Response
    {
        return ($user->can('update:volunteer-position'))
            ? Response::allow()
            : Response::deny(message: 'U hebt geen toestemming om de gegevens van de vrijwilligers positie te wijzigen.');
    }

    public function view(User $user, VolunteerPosition $volunteerPosition): Response
    {
        return ($user->can('view:volunteer-position'))
            ? Response::allow()
            : Response::deny(message: 'U hebt geen toestemming om de vrijwilliger positie te bekijken.');
    }

    public function delete(User $user, VolunteerPosition $volunteerPosition): Response
    {
        return ($user->can('delete:volunteer-position'))
            ? Response::allow()
            : Response::deny(message: 'U hebt geen toesttemming om de vrijwilligers positie te verwijderen.');
    }
}
