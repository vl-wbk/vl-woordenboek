<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enums\ArticleStates;
use App\Enums\Visibility;
use App\Models\Note;
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

    /**
     * Attaches a note to the article.
     *
     * This method creates a new note with the given title and body, associates
     * it with the currently authenticated user as the author, and saves it to
     * the article's notes relationship.
     *
     * @param  string       $title       The title of the note.
     * @param  string|null  $note        The body of the note (optional).
     * @return self<\App\Models\Article> Returns the current ArticleBuilder instance for method chaining.
     */
    public function attachNote(string $title, ?string $note = null): self
    {
        $note = new Note(attributes: ['title' => $title, 'author_id' => auth()->id(), 'body' => $note]);
        $this->model->notes()->save(model: $note);

        return $this;
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
        return ! $this->isHidden();
    }
}
