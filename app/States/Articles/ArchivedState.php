<?php

declare(strict_types=1);

namespace App\States\Articles;

use Illuminate\Support\Facades\DB;

/**
 * Represents the archived state of a dictionary article.
 *
 * This state is used to indicate that an article has been temporarily removed from public visibility.
 * While archived, the article remains in the system for historical reference or potential future restoration.
 * This state provides flexibility in content management by allowing articles to be archived without permanent deletion.
 *
 * Articles in this state can be transitioned back to a published state if needed, ensuring that no data is lost
 * and that content can be restored when it becomes relevant again.
 *
 * @package App\States\Articles
 */
final class ArchivedState extends ArticleState
{
    /**
     * Transitions the article from the archived state back to the published state.
     *
     * This method is used to restore an article that was previously archived. It changes the article's state
     * to "published" and ensures that the change is persisted to the database. This functionality is useful
     * when archived content becomes relevant again or when an article was archived by mistake.
     *
     * @return bool
     */
    public function transitionToReleased(): bool
    {
        return DB::transaction(function (): bool {
            $this->article->unarchive();

            return true;
        });
    }
}
