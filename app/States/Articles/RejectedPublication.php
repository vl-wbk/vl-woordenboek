<?php

declare(strict_types=1);

namespace App\States\Articles;

use App\Enums\ArticleStates;
use App\States\Articles\ArticleState;

final class RejectedPublication extends ArticleState
{
    public function transitionToApproved(): void
    {
        $this->article->update(attributes: ['state' => ArticleStates::Approval]);
    }
}
