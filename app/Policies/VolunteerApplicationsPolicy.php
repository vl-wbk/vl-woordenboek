<?php

declare(strict_types=1);

namespace App\Policies;

use App\Attributes\Todo;
use App\Enums\Volunteers\ApplicationState;
use App\Models\User;
use App\Models\VolunteerApplications;
use Illuminate\Auth\Access\Response;

#[Todo(message: 'provide docblocks for this policy method.')]
final class VolunteerApplicationsPolicy
{
    public const Approve = 'approve';
    public const Reject = 'reject';

    /**
     * @var list<string>
     */
    public static array $permissionPrefixes = ['viewAny', 'view', 'goedkeuren', 'afwijzen'];

    public function approve(User $user, VolunteerApplications $volunteerApplications): Response
    {
        return ($user->can('goedkeuren:volunteer-applications') && $volunteerApplications->state->is(ApplicationState::Open))
            ? Response::allow()
            : Response::deny(message: 'U hebt geen toestemming om de aanmeldinng goed te keuren.');
    }

    public function reject(User $user, VolunteerApplications $volunteerApplications): Response
    {
        return ($user->can('afwijzen:volunteer-applications') && $volunteerApplications->state->is(ApplicationState::Open))
            ? Response::allow()
            : Response::deny(message: 'U hebt geen toestemming om de aanmelding af te wijzen.');
    }

    public function viewAny(User $user): Response
    {
        return $user->can('view-any:volunteer-applications')
            ? Response::allow()
            : Response::deny(message: 'U hebt geen toestemming om de lijst met aanmeldingen te bekijken.');
    }

    public function view(User $user, VolunteerApplications $volunteerApplications): Response
    {
        return $user->can('view:volunteer-applications')
            ? Response::allow()
            : Response::deny(message: 'U hebt geen toestemming om de gegevens van de aanmelding te bekijken.');
    }
}
