<?php

declare(strict_types=1);

namespace App\States\Articles;

use App\Enums\ArticleStates;

final class DraftState extends ArticleState
{
    public function transitionToApproved(): void
    {
        $this->article->update(attributes: ['state' => ArticleStates::Approval]);
    }
}
