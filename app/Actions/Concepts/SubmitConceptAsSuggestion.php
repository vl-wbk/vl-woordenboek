<?php

declare(strict_types=1);

namespace App\Actions\Concepts;

use App\Models\Article;
use App\Models\Concept;
use Illuminate\Support\Facades\DB;

final readonly class SubmitConceptAsSuggestion
{
    public function handle(Concept $concept): Article
    {
        return DB::transaction(function () use ($concept): Article {
            $attributes = $concept->replicate()->getAttributes();

            $article = new Article();
            $article->forceFill($attributes)->save();

            if ($concept->relationLoaded('regions') || $concept->regions()->exists()) {
                $article->regions()->sync($concept->regions->pluck('id'));
            }

            $concept->delete();

            return $article;
        });
    }
}
