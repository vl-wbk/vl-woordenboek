<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\Article;
use Throwable;
use App\Enums\ArticleStates;
use App\Notifications\SendoutPublicationNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Deprecated;

/**
 * ArticleBuilder provides custom query and state management feature for articles.
 *
 * This class extends Laravel's Eloquent Builder to include methods for managing the lifecycle of articles, specifically archiving and unarchiving them.
 * It encapsulates the logic for these operations, ensuring that state transitions are handled consistently and securely within database transactions.
 *
 * @template TModelClass of \App\Models\Article
 * @extends Builder<Article>
 *
 * @package App\Builders
 */
final class ArticleBuilder extends Builder
{
    /**
     * Scope: constrain the query to 'Live' articles only.
     *
     * This method applies a dual constraint filter:
     * 1. Presence: The 'published_at' timestamp must not be null.
     * 2. Chronology: The timestamp must be in the past or equal to 'now'.
     *
     * @internal This scope allows for "scheduled publishing." Articles with a future 'published_at' date will be excluded
     * from this scope until that time is reached.
     *
     * NOTE: If using result caching, ensure the cache TTL accounts for upcoming scheduled publication timestamps.
     *
     * @return Builder<Article>
     */
    public function published(): Builder
    {
        return $this->whereNotNull("published_at")->where("published_at", "<=", now());
    }

    /**
     * Scope: filter the query to include only archived records.
     *
     * This method targets the 'archived_at' timestamp. If the timestamp is present, the record is considered part of the archive.
     * This uses an 'orWhere' clause. When chaining this with other scopes, ensure you wrap this call in a logical
     * grouping (Parameter Grouping) to prevent gloabl scope pollution.
     *
     * @return Builder<Article>
     */
    public function archived(): Builder
    {
        return $this->orWhereNotNull("archived_at");
    }

    public function isEditable(): bool
    {
        return $this->model->state->in([ArticleStates::New, ArticleStates::Draft, ArticleStates::Archived]) &&
            !$this->model->state->is(ArticleStates::Approval);
    }

    /**
     * State check: determines if the current model instance is archived.
     *
     * Unline the 'archived' scope, this operates on the hydrated model's attribute property.
     * It is used to drive UI logic, authorization checks, or state-dependent actions on a speific article.
     *
     * NOTE: This relies on the 'archived_at' attribute being present in the model's current attribute
     * array (ensure it is selected in your query).
     *
     * @return bool true if the record has been archived
     */
    public function isArchived(): bool
    {
        /** @phpstan-ignore-next-line */
        return !is_null($this->model->archived_at);
    }

    /**
     * Archives the current article with an optional reason.
     *
     * This method transitions the article's state to "Archived" and records the archiving reason, the timestamp of the action, and the user who performed it.
     * The operation is wrapped in a database transaction to ensure data consistency.
     *
     * @param string|null $archivingReason The optional reason for archiving the article.
     *
     * @throws Throwable when the database transaction couldn't complete successfully
     */
    public function archive(?string $archivingReason = null, int|string|null $redirectArticleId): bool
    {
        return DB::transaction(function () use ($archivingReason, $redirectArticleId): bool {
            return $this->model
                ->fill(
                    attributes: [
                        "state" => ArticleStates::Archived,
                        "archiving_reason" => $archivingReason,
                        "published_at" => null,
                        "archived_at" => now(),
                        "redirect_article_id" => $redirectArticleId,
                    ],
                )
                ->archiever()
                ->associate(Auth::user())
                ->save();
        });
    }

    /**
     * Restores the current article from the archived state to the published state.
     *
     * This method transitions the article's state back to "Published" and clears any archiving-related data, such as the archiving reason and timestamp.
     * The operation is wrapped in a database transaction to ensure data consistency.
     *
     * @throws Throwable
     */
    #[Deprecated("Should be refactored to a general publish action in the ArticleBuilder")]
    public function unarchive(): void
    {
        DB::transaction(function (): void {
            $this->model->update(
                attributes: [
                    "state" => ArticleStates::New,
                    "archiving_reason" => null,
                    "feedback" => null,
                    "published_at" => null,
                    "archived_at" => null,
                    "publisher_id" => null,
                    "redirect_article_id" => null,
                    "editor_id" => null,
                ],
            );

            $this->model->author->notify(new SendoutPublicationNotification($this->model));
        });
    }

    /**
     * Checks if the article is hidden.
     * This method determines whether the article is currently hidden from public view by checking if the `published_at` attribute is null.
     *
     * @return bool True if the article is hidden, false otherwise.
     */
    public function isHidden(): bool
    {
        return is_null($this->model->published_at);
    }

    /**
     * Checks if the article is published.
     *
     * This method determines whether the article is currently published and visible to the public.
     * It returns the opposite of the `isHidden()` method.
     *
     * @return bool True if the article is published, false otherwise.
     */
    public function isPublished(): bool
    {
        return !$this->isHidden() && $this->model->published_at->isPast();
    }
}
