<?php

namespace App\Policies;

use App\Enums\VolunteerApplicationState;
use App\Models\User;
use App\Models\VolunteerApplication;
use Illuminate\Auth\Access\Response;

/**
 * @todo document policy class
 */
final class VolunteerApplicationPolicy
{
    const Approve = 'approve'; 
    const Reject = 'reject';

    public static array $permissionPrefixes = [
        'view', 'viewAny', 'delete', 'deleteAny', 'approve', 'reject'
    ];

    public function viewAny(User $user): Response
    {
        if ($user->can('view-any:volunteer-application')) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function view(User $user, VolunteerApplication $volunteerApplication): Response
    {
        if ($user->can('view:volunteer-application')) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function delete(User $user, VolunteerApplication $volunteerApplication): Response
    {
        if ($user->can('delete:volunteer-application')) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function deleteAny(User $user): Response 
    {
        if ($user->can('delete-any:volunteer-application')) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function approve(User $user, VolunteerApplication $volunteerApplication): Response
    {
        if ($user->can('approve:volunteer-application') && $volunteerApplication->state->is(VolunteerApplicationState::Open)) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function reject(User $user, VolunteerApplication $volunteerApplication): Response
    {
        if ($user->can('reject:volunteer-application') && $volunteerApplication->state->is(VolunteerApplicationState::Open)) {
            return Response::allow();
        }

        return Response::deny();
    }
}
