<?php

declare(strict_types=1);

namespace App\States\Articles;

use App\Enums\ArticleStates;

final class ApprovalState extends ArticleState
{
    public function transitionToEditing(): void
    {
        $this->article->update(attributes: ['state' => ArticleStates::Draft]);
    }

    public function transitionToReleased(): void
    {
        $this->article->update(attributes: ['state' => ArticleStates::Published]);
    }

    public function transitionToArchived(): void
    {
        $this->article->update(attributes: ['state' => ArticleStates::Archived]);
    }
}
