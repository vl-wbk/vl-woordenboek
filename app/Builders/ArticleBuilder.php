<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enums\ArticleStates;
use App\Enums\Visibility;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Deprecated;

/**
 * ArticleBuilder provides custom query and state management functionality for articles.
 *
 * This class extends Laravel's Eloquent Builder to include methods for managing the lifecycle of articles, specifically archiving and unarchiving them.
 * It encapsulates the logic for these operations, ensuring that state transitions are handled consistently and securely within database transactions.
 *
 * @template TModelClass of \App\Models\Article
 * @extends Builder<\App\Models\Article>
 *
 * @package App\Builders
 */
final class ArticleBuilder extends Builder
{
    /**
     * Archives the current article with an optional reason.
     *
     * This method transitions the article's state to "Archived" and records the archiving reason, the timestamp of the action, and the user who performed it.
     * The operation is wrapped in a database transaction to ensure data consistency.
     *
     * @param string|null $archivingReason The reason for archiving the article (optional).
     * @return void
     */
    public function archive(?string $archivingReason = null): void
    {
        DB::transaction(function () use ($archivingReason): void {
            $this->model->update(attributes: ['state' => ArticleStates::Archived, 'archiving_reason' => $archivingReason, 'published_at' => null, 'archived_at' => now()]);
            $this->model->archiever()->associate(auth()->user())->save();
        });
    }

    /**
     * Restores the current article from the archived state to the published state.
     *
     * This method transitions the article's state back to "Published" and clear any archiving-related data, such as the archiving reason and timestamp.
     * The operation is wrapped in a database transaction to ensure data consistency.
     *
     * @return void
     */
    #[Deprecated('Should be refzactored to a general publish action in the ArticleBuilder')]
    public function unarchive(): void
    {
        DB::transaction(function (): void {
            $this->model->update(attributes: ['state' => ArticleStates::Published, 'archiving_reason' => null, 'published_at' => now(), 'archived_at' => null]);
            $this->model->archiever()->associate(null)->save();
        });
    }

    public function isHidden(): bool
    {
        return is_null($this->model->published_at);
    }

    public function isPublished(): bool
    {
        return ! $this->isHidden();
    }
}
