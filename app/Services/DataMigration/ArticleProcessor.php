<?php

declare(strict_types=1);

namespace App\Services\DataMigration;

use App\Jobs\DataMigration\ConvertHardReturns;
use App\Jobs\DataMigration\StandarizeInternalHyperlinks;
use App\Models\Article;
use Illuminate\Support\Facades\Bus;
use RuntimeException;
use Throwable;

/**
 * Handles the creation and processing of articles during data migration.
 *
 * This class is responsible for creating articles in the database and dispatching a chain of jobs to process the article's content.
 * It ensures that any errors during creation or job dispatching are properly handled and reported with meaningful error messages.
 *
 * @package App\Services
 */
final readonly class ArticleProcessor
{
    /**
     * Handles the creation and processing of articles during the data migration.
     *
     * This method creates an article in the database using the provided data and dispatches a chain of jobs to process the article's content.
     * If any errors occur during article creation or job dispatching, they are caught and rethrown as RuntimeExceptions with detailed error messages.
     *
     * @param  array $articleData  The data for the article to be created. This should include all required fields for the `Article` model.
     * @return Article             The created article instance.
     *
     * @throws RuntimeException If the article creation fails or if the jobs cannot be dispatched.
     */
    public function process(array $articleData): Article
    {
        try { // To create the article in the database.
            $article = Article::create($articleData);
        } catch (\Throwable $th) { // Throw an exception if article creation fails
            throw new RuntimeException("Failed to create article '{$articleData['word']}': {$th->getMessage()}", 0, $th);
        }

        try { // To dispatch a chain of jibs to process the article's content standarization.
            Bus::chain([
                new ConvertHardReturns($article),
                new StandarizeInternalHyperlinks($article),
            ])->dispatch();
        } catch (Throwable $th) { // Throw an exception if the job dispatching fails
            throw new RuntimeException("Failed to dispatch jobs for article #{$article->id} - '{$article->word}': {$th->getMessage()}", 0, $th);
        }

        // Return the created article instance
        return $article;
    }
}
