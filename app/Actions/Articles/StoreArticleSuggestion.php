<?php

declare(strict_types=1);

namespace App\Actions\Articles;

use App\Data\SuggestionData;
use App\Models\Article;
use Illuminate\Support\Facades\DB;

final readonly class StoreArticleSuggestion
{
    public function execute(SuggestionData $suggestionData): void
    {
        DB::transaction(function () use ($suggestionData): void {
            $suggestion = Article::query()->create($suggestionData->except('regions')->toArray());
            $suggestion->regions()->sync($suggestionData->regions);

            if (! is_null($suggestionData->creator_id)) {
                $suggestion->creator()->associate($suggestionData->creator_id)->save();
            }
        });
    }
}
