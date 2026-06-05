<?php

declare(strict_types=1);

namespace App\States\Posts;

use App\Attributes\Todo;

#[Todo(message: 'Document this interface with docblocks', priority: 'low')]
interface PublicationStateContract
{
    public function transitionToDraft(): bool;

    public function transitionToPublished(): bool;
}
