<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Etymology;
use App\Models\User;
use App\UserTypes;

final readonly class EtymologyPolicy
{
    public function delete(User $user, Etymology $etymology): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer]);
    }
}
