<?php

declare(strict_types=1);

namespace App\Models\Concerns\Articles;

use App\Enums\ArticleStates;
use App\Contracts\States\ArticleStateContract;
use App\States\Articles\ArticleState;
use App\States\Articles;

trait ManagesArticleStates
{
    /**
     * Returns the appropriate Article State instance based on the current article status.
     *
     * This method uses a `match` expression to determine the current state of the dictionary article based on its state.
     * It then returns an instance of the corresponding state class, which handles specific behaviors and transitions of that state.
     * Each article state maps to a different state class; ensuring the current state logic is applied at any given point in the article lifecycle.
     *
     * Example states flow: New -> Draft -> Approval -> Published -> Archived
     *
     * @return ArticleStateContract - The corresponding state class for the current dictionary article
     */
    public function articleStatus(): ArticleStateContract
    {
        return match ($this->state) {
            ArticleStates::ExternalData => new Articles\ExternalData($this),
            ArticleStates::New => new Articles\Suggestion($this),
            ArticleStates::Draft => new Articles\Draft($this),
            ArticleStates::Approval => new Articles\Approval($this),
            ArticleStates::Published => new Articles\Published($this),
            ArticleStates::Archived => new Articles\Archived($this),
            ArticleStates::RejectedPublication => new Articles\RejectedPublication($this),
            ArticleStates::RejectedSuggestion => new Articles\RejectedSuggestion($this)
        };
    }

    public function isAwaitingModeration(): bool
    {
        return $this->state->is(ArticleStates::New);
    }

    public function isRejectedSuggestion(): bool
    {
        return $this->state->is(ArticleStates::RejectedSuggestion);
    }
}
