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
            : Response::deny(message: 'U heeft geen toestemming om dit naslagwerk te bekijken.');
    }

    public function viewAny(User $user): Response
    {
        return $user->can('woordenboek-ondersteuning')
            ? Response::allow()
            : Response::deny(message: 'u geeft geen toestemming om de lijst met naslagwerken te bekijken.');
    }

    public function create(User $user): Response
    {
        return $user->can('woordenboek-ondersteuning')
            ? Response::allow()
            : Response::deny(message: 'U heeft geen toestemmintg om nieuwe naslagwerken aan te maken.');
    }

    public function update(User $user, ReferenceWork $referenceWork): Response
    {
        return $user->can('woordenboek-ondersteuning')
            ? Response::allow()
            : Response::deny(message: 'U heeft geen toestemming om naslagwerken te bewerken.');
    }

    public function delete(User $user, ReferenceWork $referenceWork): Response
    {
        return $user->can('woordenboek-ondersteuning')
            ? Response::allow()
            : Response::deny(message: 'U heeft geen toestemming om naslagwerken te verwijderen.');
    }
}
