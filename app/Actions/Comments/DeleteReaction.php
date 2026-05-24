<?php

declare(strict_types=1);

namespace App\Actions\Comments;

use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * The DeleteReaction class is an action responsible for handling the deletion of a specific comment (referred to as a "reaction" in this context).
 * This class encapsulates the business logic for removing a comment from the database, ensuring that the operation is performed atomically within a database transaction.
 *
 * This action promotes a clean architecture by separating the concerns of handling HTTP requests from the core business logic of data manipulation.
 * It is designed to be a single, focused operation that can be easily tested and maintained.
 * The `final readonly` declaration ensures that this class cannot be extended and its properties cannot be modified after construction, promoting immutability and predictability.
 *
 * @package App\Actions\Comments
 */
final readonly class DeleteReaction
{
    /**
     * Handles the process of deleting a specific comment.
     *
     * This method orchestrates the removal of a `Comment` record from the database.
     * It takes a `Comment` model instance, representing the comment to be deleted.
     *
     * The core deletion operation, `$comment->delete()`, is wrapped within a database transaction using `DB::transaction`.
     * This is a critical design choice for maintainability and data integrity, ensuring that the deletion process is atomic.
     * If any part of the deletion fails (e.g., database error or related constraints), the entire operation will be rolled back, preventing partial or inconsistent data.
     *
     * The `delete()` method on the `Comment` model handles the actual removal of the record from the database.
     * If the `Comment` model uses soft deletes, this will typically mark the record as deleted without physically removing it.
     *
     * Developers should note that any authorization checks for deleting a comment (e.g., ensuring the current user has permission to delete this specific comment)
     * should ideally be performed *before* calling this action, perhaps in a policy or a request class. This action focuses purely on the deletion logic.
     *
     * @param  Comment $comment   The comment model instance to be deleted.
     * @return bool               Returns `true` if the comment was successfully deleted (or soft-deleted), `false` otherwise. The result is derived from the `delete()` method of the Eloquent model.
     *
     * @throws Throwable when the database transaction couldn't perform successfully
     */
    public function handle(Comment $comment): bool
    {
        return DB::transaction(callback: fn() => $comment->delete());
    }
}
