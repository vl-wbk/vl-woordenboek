<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Blog;

use App\Actions\Comments\StoreReaction;
use App\Concerns\RateLimitSubmission;
use App\Http\Controllers\Web\Blog\PostsController;
use App\Http\Requests\Comments\StoreCommentRequest;
use App\Models\Blog;
use BeyondCode\Comments\Comment;
use Illuminate\Http\RedirectResponse;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;

#[Middleware(middleware: ['auth', 'forbid-banned-user', 'verified'])]
final class CommentsController
{
    use RateLimitSubmission;

    #[Post(uri: '/reageer/{blog}', name: 'comment:create')]
    public function store(Blog $blog, StoreCommentRequest $storeCommentRequest, StoreReaction $storeReaction): RedirectResponse
    {
        /** @phpstan-ignore-next-line */
        return $this->attemptSubmissionWithRateLimiting($storeCommentRequest, 'reaction', function () use ($storeReaction, $storeCommentRequest, $blog): RedirectResponse {
            $storeReaction->handle($blog, $storeCommentRequest);

            return redirect()->action([PostsController::class, 'show'], parameters: $blog)->withFragment('reacties');
        });
    }

    public function delete(Comment $comment): RedirectResponse
    {

    }
}
