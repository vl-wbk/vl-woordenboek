<?php

declare(strict_types=1);

namespace App\States\Posts;

/**
 * @todo Document this interface
 */
interface PublicationStateContract
{
    public function transitionToDraft(): bool;

    public function transitionToPublished(): bool;
}
