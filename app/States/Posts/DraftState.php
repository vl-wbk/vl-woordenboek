<?php

declare(strict_types=1);

namespace App\States\Posts;

use App\Exceptions\StateTransitionException;

final class DraftState extends PublicationState
{
    public function transitionToDraft(): bool
    {
        throw new StateTransitionException('Cannot transition to the draft state on the current state');
    }
}
