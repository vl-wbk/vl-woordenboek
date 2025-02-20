<?php

declare(strict_types=1);

namespace App\Actions\Articles;

use App\Data\ArticleData;
use App\Models\Word;
use Illuminate\Support\Facades\DB;

/**
 * Updates an dictionary article with a transaction.
 * This action performs the following steps within a database transaction:
 *
 * 1. Updates the provided `Word` model (which represents an article) with the data from the `ArticleObjectData` DTO.
 * 2. If the update is successful, sets the current authenticated user as the editor of the article.  This is done using the `setEditor()` method on the `Word` model.
 *
 * The database transaction ensures that both the article update and the editor assignment are performed atomically.
 * If either operation fails, the entire transaction is rolled back, preventing data inconsistencies.
 *
 * @package App\Actions\Articles
 */
final readonly class UpdateArticle
{
    /**
     * Performs the logic for updating the dictionary article.
     *
     * @param  Word         $article  The Word model instance representing the article to be updated.
     * @param  ArticleData  $data     The data transfer object containing the updated article data.
     * @return bool                   True if the update was successful (including setting the editor), false otherwise.
     */
    public function __invoke(Word $article, ArticleData $articleObjectData): bool
    {
        return DB::transaction(function () use ($article, $articleObjectData): bool {
            if ($updateProcess = $article->update(attributes: $articleObjectData->except('regions')->toArray())) {
                $article->setCurrentUserAsEditor();
                $article->syncRegions($articleObjectData->regions);
            }

            return $updateProcess;
        });
    }
}
