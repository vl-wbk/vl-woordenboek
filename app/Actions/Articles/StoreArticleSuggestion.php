<?php

declare(strict_types=1);

namespace App\Actions\Articles;

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
     * Processes and persists a new article suggestion.
     *
     * This method runs within a database transaction to ensure data integrity.
     * It icreates the article record, snchronizes the associated regions, and deletes the related concept if one
     * is provided. Finally, it triggers a success notification for the user.
     *
     * @param  SuggestionData $suggestionData The data transfer object carrying all details for the new article suggestion.
     * @param  Concept|null   $concept        The concept version of the dictionary article (database entity).
     * @return Article                        The newly created article as suggestion.
     *
     * @throws Throwable                      If the database transaction fails.
     */
    public function execute(SuggestionData $suggestionData, ?Concept $concept = null): Article
    {
        $suggestion = DB::transaction(function () use ($suggestionData, $concept): Article {
            $data = $suggestionData->except('regions')->toArray(); // Merge author_id into data array to insert in one query.
            $data['author_id'] = auth()->id(); // Returns null for guests if column is nullable.

            $article = Article::create($data);
            $article->regions()->sync($suggestionData->regions);

            $concept?->delete();

            return $article;
        });

        flash($this->getFlashMessage(), 'alert-success');

        return $suggestion;
    }

    /**
     * Generates a localized success message based on the user's authentication status.
     *
     * This method returns a message informing the user that their suggestion has been received.
     * It provides a different call-to-action depending on whether the user is authenticated, encouraging guest users to register for status tracking.
     *
     * @return string The translated success message.
     */
    private function getFlashMessage(): string
    {
        return auth()->check()
            ? trans('We hebben je suggestie goed ontvangen en zullen er zo snel mogelijk naar kijken. Op je account kun je de status opvolgen van elke suggestie die je hebt ingediend.')
            : trans('We hebben je suggestie goed ontvangen en zullen er zo snel mogelijk naar kijken. Wil je weten wanneer je suggestie online komt? Registreer je dan als gebruiker, dan kun je de status opvolgen van elke suggestie die je hebt ingediend.');
    }
}
