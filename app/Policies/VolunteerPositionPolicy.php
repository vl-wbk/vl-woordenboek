<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\VolunteerPosition;
use App\Settings\VolunteerSettings;
use Illuminate\Auth\Access\Response;

final class VolunteerPositionPolicy
{
    public static array $permissionPrefixes = [
        'viewAny', 'view', 'update', 'delete', 'create' 
    ]; 

    public function apply(User $user, VolunteerPosition $volunteerPosition): Response
    {
        if ($volunteerPosition->is_open && app(VolunteerSettings::class)->pageRegistrationActive) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function viewAny(User $user): Response
    {
        return ($user->can('view-any:volunteer-position')) 
            ? Response::allow()
            : Response::deny();
    }

    public function create(User $user): Response
    {
        return ($user->can('create:volunteer-position')) 
            ? Response::allow()
            : Response::deny();
    }

    public function update(User $user, VolunteerPosition $volunteerPosition): Response
    {
        return ($user->can('update:volunteer-position')) 
            ? Response::allow()
            : Response::deny();
    }

    public function view(User $user, VolunteerPosition $volunteerPosition): Response
    {
        return ($user->can('view:volunteer-position')) 
            ? Response::allow()
            : Response::deny();
    }

    public function delete(User $user, VolunteerPosition $volunteerPosition): Response
    {
        return ($user->can('delete:volunteer-position'))
            ? Response::allow()
            : Response::deny();
    }
}
