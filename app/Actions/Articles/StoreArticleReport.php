<?php

declare(strict_types=1);

namespace App\Actions\Articles;

use App\Data\ArticleReportData;
use App\Http\Requests\Articles\StoreReportRequest;
use App\Models\Article;
use App\Models\ArticleReport;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * The StoreArticleReport class is an action responsible for handling the creation and storage of a new report associated with a specific article.
 * This class encapsulates the business logic for creating an article report, ensuring that the operation is performed atomically within a database transaction.
 *
 * This action promotes a clean architecture by separating the concerns of handling HTTP requests from the core business logic of storing data.
 * It is designed to be a single, focused operation that can be easily tested and maintained.
 * The `final readonly` declaration ensures that this class cannot be extended and its properties cannot be modified after construction, promoting immutability and predictability.
 *
 * @see Article             - The Article model to which the report will be attached.
 * @see ArticleReport       - The ArticleReport model that will be created.
 * @see StoreReportRequest  - The validated request containing report data.
 * @see ArticleReportData   - The Data Transfer Object used for preparing report attributes.
 *
 * @package App\Actions\Articles
 */
final readonly class StoreArticleReport
{
    /**
     * Executes the process of storing a new article report.
     *
     * This method orchestrates the creation of a new ArticleReport record in the database.
     * It takes a `StoreReportRequest` instance, which provides the validated report content and an `Article` model instance, representing the target article to which the report will be associated.
     *
     * The entire operation is wrapped within a database transaction using DB::transaction.
     * This is a critical design choice for maintainability and data integrity, ensuring that the report creation process is atomic. If any part of the creation fails (e.g., database error),
     * the entire operation will be rolled back, preventing partial or inconsistent data.
     *
     * Inside the transaction:
     *
     * 1. The `reportData` is extracted from the `StoreReportRequest` using `getData()`. This data is expected to be a `ArticleReportData` DTO.
     * 2. A new `ArticleReport` instance is created and associated with the `Article` using the `newArticleReportInstance` private helper method. This method handles saving the report via the article's `reports()` relationship.
     * 3. The `tap` helper is used to modify the newly created `articleReport` instance.
     *
     * Specifically, `setCurrentUserAsAuthor()` is called on the `articleReport`.
     * This method (assumed to exist on the `ArticleReport` model or a trait it uses) is responsible for associating the currently authenticated user as the author of the report.
     * The phpstan-ignore-next-line comment is present to suppress potential static analysis warnings related to the `tap` function's return type or the method call within it.
     *
     * Developers should note that the `StoreReportRequest` is expected to have already performed all necessary validation, so this method focuses solely on the storage logic and attribute population.
     *
     * @param  StoreReportRequest  $storeReportRequest    The validated request object containing the report's content and related data.
     * @param  Article             $article               The article to which the report will be attached.
     * @return void                                       This method does not return a value; its side effect is the creation of a new `ArticleReport` record in the database.
     *
     * @throws Throwable When the database transaction couldn't completed successfully
     */
    public function execute(StoreReportRequest $storeReportRequest, Article $article): void
    {
        DB::transaction(function () use ($storeReportRequest, $article): void {
            $reportData = $storeReportRequest->getData();
            $articleReport = $this->newArticleReportInstance($article, $reportData);

            /** @phpstan-ignore-next-line */
            tap($articleReport, function (ArticleReport $articleReport): void {
                $articleReport->setCurrentUserAsAuthor();
            });
        });
    }

    /**
     * Creates and saves a new `ArticleReport` instance associated with a given article.
     *
     * This private helper method is responsible for instantiating an ArticleReport model with the provided ArticleReportData and then saving it through the Article model's reports() relationship.
     * This ensures that the new report is correctly linked to its parent article in the database.
     *
     * @param  Article            $article              The article to which the report will be linked.
     * @param  ArticleReportData  $articleReportData    The Data Transfer Object containing the attributes for the new article report.
     * @return ArticleReport|bool                       The newly created `ArticleReport` model instance on success, or `false` if the save operation fails (though Eloquent's `save` typically returns the model or throws an exception on failure).
     */
    private function newArticleReportInstance(Article $article, ArticleReportData $articleReportData): ArticleReport|bool
    {
        return $article->reports()->save(
            model: new ArticleReport(attributes: $articleReportData->toArray()),
        );
    }
}
