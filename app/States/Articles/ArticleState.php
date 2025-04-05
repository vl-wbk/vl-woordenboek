<?php

declare(strict_types=1);

namespace App\States\Articles;

use App\Contracts\States\ArticleStateContract;
use App\Models\Article;
use LogicException;

class ArticleState implements ArticleStateContract
{
    public function __construct(
        public readonly Article $article,
    ) {}

    public function transitionToApproved(): void
    {
        throw new LogicException('The method transitionToApproved() is not allowed on the current state.');
    }

    public function transitionToArchived(?string $archivingReason = null): void
    {
        throw new LogicException('The method transitionToArchived() is not alllowed in the current state.');
    }

    public function transitionToEditing(): void
    {
        throw new LogicException('The method tran   sitionTOEditing() is not allowed in the current state.');
    }

    public function transitionToReleased(): void
    {
        throw new LogicException('The method transitionToReleased() is not allowed on the current state.');
    }

    public function transitionToSuggestion(): bool
    {
        throw new LogicException('The method transitionToSuggestion() is not allowed on the current state.');
    }
}
