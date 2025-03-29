<?php

declare(strict_types=1);

namespace App\States\Articles;

use App\Enums\ArticleStates;

final class PublishedState extends ArticleState
{
    public function transitionToArchived(?string $archivingReason = null): void
    {
        $this->article->archive($archivingReason);
    }
}
