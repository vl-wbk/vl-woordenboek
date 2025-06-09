<?php

declare(strict_types=1);

namespace App\States\Posts;

final class PublishedState extends PublicationState
{
    public function transitionToPublished(): bool
    {
        return false;
    }
}
