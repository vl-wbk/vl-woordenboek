<?php

declare(strict_types=1);

namespace App\States\Articles;

use App\Contracts\States\ArticleStateContract;
use App\Models\Article;
use LogicException;

/**
 * ArticleState provides a base implementation of the ArticleStateContract for managing article state transitions.
 *
 * This class acts as a default state handler for an article and implements all methods defined in the ArticleStateContract.
 * However, each transition method in this default implementation throws a LogicException, indicating that the transition is not allowed for the current state.
 *
 * This design encourages developers to extend or override these methods in concrete state classes to define valid transitions and behaviors for a specific article state.
 * By default, if a transition is not supported by the current state, a clear exception message is provided.
 *
 * @property Article $article The article whose state is being managed.
 *
 * @package App\States\Articles
 */
class ArticleState implements ArticleStateContract
{
    public function __construct(
        public readonly Article $article,
    ) {}

    /**
     * {@inheritDoc}
     * @throws LogicException Always, indicating that this transition is not allowed in the current state.
     */
    public function transitionToApproved(): void
    {
        throw new LogicException('The method transitionToApproved() is not allowed on the current state.');
    }

    /**
     * {@inheritDoc}
     * @throws LogicException Always, indicating that this transition is not allowed in the current state.
     */
    public function transitionToArchived(?string $archivingReason = null): void
    {
        throw new LogicException('The method transitionToArchived() is not alllowed in the current state.');
    }

    /**
     * {@inheritDoc}
     * @throws LogicException Always, indicating that this transition is not allowed in the current state.
     */
    public function transitionToEditing(?string $reason = null): bool
    {
        throw new LogicException('The method tran   sitionTOEditing() is not allowed in the current state.');
    }

    /**
     * {@inheritDoc}
     * @throws LogicException Always, indicating that this transition is not allowed in the current state.
     */
    public function transitionToReleased(): bool
    {
        throw new LogicException('The method transitionToReleased() is not allowed on the current state.');
    }

    /**
     * {@inheritDoc}
     * @throws LogicException Always, indicating that this transition is not allowed in the current state.
     */
    public function transitionToSuggestion(): bool
    {
        throw new LogicException('The method transitionToSuggestion() is not allowed on the current state.');
    }

    public function transitionToExternalData(): bool
    {
        throw new LogicException('The method transitionToExternalData() is not allowed on the current state.');
    }
}
