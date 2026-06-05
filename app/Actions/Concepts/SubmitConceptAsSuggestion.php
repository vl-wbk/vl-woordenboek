<?php

declare(strict_types=1);

namespace App\Actions\Concepts;

use App\Models\Article;
use App\Models\Concept;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Converts a concept into a published article suggestion.
 *
 * Replicates the concept's attributes onto a new Article record, transfers any associated region relationships,
 * and deletes the originating concept - all within a single database transaction so that a failure at any step
 * leaves the database unchanged.
 *
 * @package App\Actions\Concepts
 */
final readonly class SubmitConceptAsSuggestion
{
    /**
     * Promote the given concept to an article suggestion and remove the source concept.
     *
     * replicates the concept's raw attrbiutes onto a new Article instance using forceFill to bypass any
     * guarded fields, then syncs the concept's regions to the new article if the relationship is either loaded
     * or confirmed to exist in the database. The concept is deleted only after the article and its relations have
     * been persisted successfully. The entire operation is wrapped in a transaction so that no partial state
     * is comitted if an exception is thrown at any point.
     *
     * @param  Concept $concept The conept to promote, optionally with its regions relation already loaded.
     * @return Article          The newly created article record carrying the concept's attributes and regions.
     *
     * @throws Throwable when the concept couldn't be stored as concept in the database.
     */
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
