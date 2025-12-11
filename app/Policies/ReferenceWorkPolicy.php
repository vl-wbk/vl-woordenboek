<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ReferenceWork;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final readonly class ReferenceWorkPolicy
{
    public function view(User $user, ReferenceWork $referenceWork): Response
    {
        return $user->can('woordenboek-ondersteuning')
            ? Response::allow()
            : Response::deny();
    }

    public function viewAny(User $user): Response
    {
        return $user->can('woordenboek-ondersteuning')
            ? Response::allow()
            : Response::deny();
    }

    public function create(User $user): Response
    {
        return $user->can('woordenboek_ondersteuning')
            ? Response::allow()
            : Response::deny();
    }

    public function update(User $user, ReferenceWork $referenceWork): Response
    {
        return $user->can('woordenboek_ondersteuning')
            ? Response::allow()
            : Response::deny();
    }

    public function delete(User $user, ReferenceWork $referenceWork): Response
    {
        return $user->can('woordenboek_ondersteuning')
            ? Response::allow()
            : Response::deny();
    }
}
