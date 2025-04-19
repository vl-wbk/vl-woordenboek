<?php

declare(strict_types=1);

namespace App\Actions\Articles;

use App\Contracts\Eloquent\HandlesRelationManipulationInterface;
use App\Data\SuggestionData;
use App\Models\Article;
use App\Models\Concerns\HandlesRelationManipulation;
use Illuminate\Support\Facades\DB;

/**
 * StoreArticleSuggestion encapsulates the process of saving a new article suggestion.
 *
 * This action class accepts a SuggestionData data transfer object containing all the necessary information for an article suggestion.
 * The workflow is executed within a database transaction to ensure that all operations succeed or fail together, preserving data consistency.
 *
 * The process involves:
 * - Creating a new Article record using the suggestion data, excluding the 'regions' attribute.
 * - Synchronizing the associated regions with the newly created article.
 * - Optionally associating the article with an author if a creator_id is provided.
 *
 * Future developers extending or using this class should note that any modifications to the suggestion
 * process should maintain transactional integrity, ensuring that a failure in any step will roll back the
 * entire process.
 *
 * @package App\Actions\Articles
 */
final readonly class StoreArticleSuggestion implements HandlesRelationManipulationInterface
{
    use HandlesRelationManipulation;

    /**
     * Executes the suggestion storage workflow.
     *
     * This method performs the following steps within a single transaction:
     *
     * 1. Creates a new Article record with the data provided in the SuggestionData object, while explicitly excluding the
     *    'regions' attribute. This ensures that the main article data is stored without interference from the region
     *    associations.
     *
     * 2. Synchronizes the article's associated regions using the region IDs provided in the 'regions' field of the
     *    SuggestionData. This establishes the many-to-many relationship between the article and its regions.
     *
     * 3. Checks if a creator_id is provided in the SuggestionData. If so, it associates the newly created article with
     *    the specified author. This step binds the article to its creator for tracking and future reference.
     *
     * All these operations are wrapped within a database transaction. This design ensures that if any step fails, the
     * transaction will roll back, keeping the database in a consistent state.
     *
     * @param SuggestionData $suggestionData The data transfer object carrying all details for the new article suggestion.
     * @return void
     */
    public function execute(SuggestionData $suggestionData): void
    {
        DB::transaction(function () use ($suggestionData): void {
            $suggestion = Article::query()->create($suggestionData->except('regions')->toArray());
            $this->sync($suggestion, 'regions', $suggestionData->regions);

            if (! is_null($suggestionData->creator_id)) {
                $suggestion->author()->associate($suggestionData->creator_id)->save();
            }
        });
    }
}
