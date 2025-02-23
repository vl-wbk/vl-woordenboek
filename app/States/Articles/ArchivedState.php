<?php

declare(strict_types=1);

namespace App\States\Articles;

use App\Enums\ArticleStates;

final class ArchivedState extends ArticleState
{
    public function transitionToReleased(): void
    {
        $this->article->update(attributes: ['state' => ArticleStates::Published]);
    }
}
