<?php

declare(strict_types=1);

namespace App\States\Posts;

/**
 * @todo Document this class
 */
final class PublishedState extends PublicationState
{
    public function transitionToPublished(): bool
    {
        return false;
    }
}
