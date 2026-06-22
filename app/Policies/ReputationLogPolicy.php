<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\UserTypes;
use Illuminate\Auth\Access\Response;

final readonly class ReputationLogPolicy
{
    public function before(User $user, string $ability): Response
    {
        if ($user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer])) {
            return Response::allow();
        }

        return Response::denyAsNotFound();
    }
}
