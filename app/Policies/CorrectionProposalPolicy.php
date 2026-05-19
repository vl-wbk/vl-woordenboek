<?php

declare(strict_types=1);

namespace App\Policies;

use App\Features\BetaProgramFeature;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Laravel\Pennant\Feature;

final readonly class CorrectionProposalPolicy
{
    public function before(User $user): Response
    {
        if (Feature::for($user)->active(BetaProgramFeature::class)) {
            return Response::allow();
        }

        return Response::denyAsNotFound();
    }

    public function create(User $user): Response
    {
        return Response::allow();
    }
}
