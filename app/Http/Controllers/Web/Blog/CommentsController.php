<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Blog;

use App\Actions\Comments\DeleteReaction;
use App\Actions\Comments\StoreReaction;
use App\Concerns\RateLimitSubmission;
use App\Http\Requests\Comments\StoreCommentRequest;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Spatie\RouteAttributes\Attributes\Get;
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

    #[Get('/reactie/{comment}/delete', name: 'comment:delete', middleware: 'can:delete,comment')]
    public function delete(Comment $comment, DeleteReaction $deleteReaction): RedirectResponse
    {
        $deleteReaction->handle($comment);

        return redirect()
            ->action([PostsController::class, 'show'], parameters: $comment->commentable)
            ->withFragment('reacties');
    }
}
