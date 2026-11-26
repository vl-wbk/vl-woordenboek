<?php

declare(strict_types=1);

namespace App\States\Articles;

use App\Enums\ArticleStates;
use App\States\Articles\ArticleState;
use Illuminate\Support\Facades\DB;

final class RejectedPublication extends ArticleState
{
    public function transitionToApproved(): void
    {
        DB::transaction(fn (): bool => $this->article->transitionToState(
            to: ArticleStates::Approval,
            via: __METHOD__,
            reason: 'Een redacteur heeft het artikel ingezonden ter controle.',
        ));
    }
}
