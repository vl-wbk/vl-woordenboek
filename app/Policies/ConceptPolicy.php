<?php

namespace App\Policies;

use App\Models\Concept;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final readonly class ConceptPolicy
{
    public function sendSubmission(User $user, Concept $concept): Response
    {
        return $concept->authoredBy($user)
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
