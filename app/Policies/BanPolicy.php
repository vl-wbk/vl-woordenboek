<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\UserTypes;
use Cog\Laravel\Ban\Models\Ban;

final readonly class BanPolicy
{
    public function before(User $user): bool
    {
        return $user->user_type->in([UserTypes::Administrators, UserTypes::Developer]);
    }

    public function viewAny(User $user): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer]);
    }

    public function view(): bool
    {
        return false;
    }

    public function update(): bool
    {
        return false;
    }

    public function delete(): bool
    {
        return false;
    }
}
