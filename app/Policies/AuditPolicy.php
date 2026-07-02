<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\UserTypes;
use Illuminate\Auth\Access\Response;
use OwenIt\Auditing\Models\Audit;

final readonly class AuditPolicy
{
    public const Revert = 'revert';
    public const ViewCorrection = 'viewCorrection';

    public function revert(User $user, Audit $audit): Response
    {
        $hasCorrectRole = $user->user_type->in(enums: [
            UserTypes::EditorInChief,
            UserTypes::Developer,
            UserTypes::Administrators
        ]);

        return ($audit->event === 'updated' && $hasCorrectRole)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function viewCorrection(User $user, Audit $audit): Response
    {
        return ($audit->correction_reason && $user->user_type->isNot(UserTypes::Normal))
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
