<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

final readonly class PasskeyPolicy
{
    public function delete(User $user): Response
    {
        return $user->isDeveloper() ? Response::allow() : Response::deny();
    }
}
