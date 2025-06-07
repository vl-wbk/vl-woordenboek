<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * @todo Document
 */
final readonly class BlogPolicy
{
    /** @todo Document */
    public function before(User $user): ?Response
    {
        return ($user->isAdministrator() || $user->isDeveloper())
            ? Response::allow()
            : null;
    }

    /**
     * @todo Document
     * @todo rename canComment to writeComment
     * @deprecated
     */
    public function canComment(User $user, Blog $blog): Response
    {
        return ($blog->comments_enabled && $user->hasVerifiedEmail())
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /** @todo Document */
    public function delete(User $user, Blog $blog): Response
    {
        return ($blog->author()->is($user) && $blog->status->isDraft())
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
