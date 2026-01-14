<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\VolunteerPosition;
use App\Settings\VolunteerSettings;
use Illuminate\Auth\Access\Response;

final readonly class VolunteerPositionPolicy
{
    public function apply(User $user, VolunteerPosition $volunteerPosition): Response
    {
        if ($volunteerPosition->is_open && app(VolunteerSettings::class)->pageRegistrationActive) {
            return Response::allow();
        }

        return Response::deny();
    }
}
