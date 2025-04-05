<?php

declare(strict_types=1);

namespace App\States\Articles;

use App\Enums\ArticleStates;
use Illuminate\Support\Facades\DB;

final class DraftState extends ArticleState
{
    public function transitionToApproved(): void
    {
        $this->article->update(attributes: ['state' => ArticleStates::Approval]);
    }

    public function transitionToSuggestion(): bool
    {
        return DB::transaction(function (): bool {
            return $this->article->update(attributes: [
                'state' => ArticleStates::New, 'editor_id' => null
            ]);
        });
    }
}
