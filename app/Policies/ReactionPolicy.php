<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Reaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final readonly class ReactionPolicy
{
    public function update(User $user, Reaction $reaction): Response
    {
        if ($user->can('update:article')) {
            return Response::allow();
        }

        return Response::deny();
    }
}
