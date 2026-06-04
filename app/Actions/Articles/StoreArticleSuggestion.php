<?php

declare(strict_types=1);

namespace App\Actions\Articles;

use App\Attributes\Todo;
use App\Data\SuggestionData;
use App\Models\Article;
use App\Models\Concept;
use Illuminate\Support\Facades\DB;
use Throwable;

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
 */
final readonly class StoreArticleSuggestion
{
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
     * @param  SuggestionData $suggestionData The data transfer object carrying all details for the new article suggestion.
     * @param  Concept|null   $concept        The concept version of the dictionary article (database entity).
     *
     * @throws Throwable when the database transaction couldn't be completed safely.
     */
    public function execute(SuggestionData $suggestionData, ?Concept $concept = null): Article
    {
        $suggestion = DB::transaction(function () use ($suggestionData, $concept): Article {
            // Merge author_id into data array to insert in one query.
            $data = $suggestionData->except('regions')->toArray();
            $data['author_id'] = auth()->id(); // Returns null for guests if column is nullable.

            $article = Article::create($data);
            $article->regions()->sync($suggestionData->regions);

            $concept?->delete();

            return $article;
        });

        flash($this->getFlashMessage(), 'alert-success');

        return $suggestion;
    }

    #[Todo(message: 'Write a docblock for this function', priority: 'low')]
    private function getFlashMessage(): string
    {
        return auth()->check()
            ? trans('We hebben je suggestie goed ontvangen en zullen er zo snel mogelijk naar kijken. Op je account kun je de status opvolgen van elke suggestie die je hebt ingediend.')
            : trans('We hebben je suggestie goed ontvangen en zullen er zo snel mogelijk naar kijken. Wil je weten wanneer je suggestie online komt? Registreer je dan als gebruiker, dan kun je de status opvolgen van elke suggestie die je hebt ingediend.');
    }
}
