<?php

declare(strict_types=1);

namespace App\States\Posts;

final class SubmissionState extends PublicationState
{
    public function transitionToDraft(): bool
    {
        return false;
    }
}
