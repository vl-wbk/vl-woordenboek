<?php

declare(strict_types=1);

namespace App\Actions\Comments;

use App\Http\Requests\Comments\StoreCommentRequest;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * The StoreReaction class is an action class responsible for handling the storage of a new comment (referred to as a "reaction" in this context) associated with a specific blog post.
 * This class encapsulates the business logic for creating a comment, ensuring that the operation is performed atomically within a database transaction.
 *
 * This action promotes a clean architecture by separating the concerns of handling HTTP requests from the core business logic of storing data.
 * It is designed to be a single, focused operation that can be easily tested and maintained.
 * The `final readonly` declaration ensures that this class cannot be extended and its properties cannot be modified after construction, promoting immutability and predictability.
 *
 * @see Blog                    - The Blog model to which the comment will be attached.
 * @see Comment                 - The Comment model that will be created.
 * @see StoreCommentRequest     - The validated request containing comment data.
 *
 * @package App\Actions\Comments
 */
final readonly class StoreReaction
{
    /**
     * Handles the process of storing a new comment for a given blog post.
     *
     * This method orchestrates the creation of a new `Comment` record in the database.
     * It takes a `Blog` model instance, representing the target blog post, and a StoreCommentRequest instance, which provides the validated comment content.
     *
     * The core operation, `$blog->comment(...)`, is wrapped within a database transaction using `DB::transaction`.
     * This is a critical design choice for maintainability and data integrity, ensuring that the comment creation process is atomic.
     * If any part of the comment creation fails (e.g., database error), the entire operation will be rolled back, preventing partial or inconsistent data.
     *
     * The comment method on the Blog model (which is assumed to exist and handle the actual relationship and creation of the comment) receives the comment body extracted from the request.
     *
     * Developers should note that the StoreCommentRequest is expected to have already performed all necessary validation, so this method focuses solely on the storage logic.
     *
     * @param  Blog $blog                                The blog post to which the comment will be added.
     * @param  StoreCommentRequest $storeCommentRequest  The validated request object containing the comment's content. The `reactie` string is extracted from this request.
     * @return Comment|Model                             The newly created Comment model instance.
     *
     * @throws Throwable when the database transaction couldn't complete successfully
     */
    public function handle(Blog $blog, StoreCommentRequest $storeCommentRequest): Comment|Model
    {
        return DB::transaction(
            callback: static fn(): Comment|Model => $blog->comment($storeCommentRequest->string('reactie')->toString()),
        );
    }
}
