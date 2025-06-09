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

    public function viewAny(User $user): Response
    {
        return Response::denyAsNotFound();
    }

    public function view(User $user, $blog): Response
    {
        return Response::denyAsNotFound();
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

    public function update(User $user, Blog $blog): Response
    {
        return $blog->author()->is($user)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function publish(User $user, Blog $blog): Response
    {
        return $blog->author()->is($user)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function delete(User $user, Blog $blog): Response
    {
        return $blog->author()->is($user)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function undoPublication(User $user, Blog $blog): Response
    {
        return $blog->author()->is($user)
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
