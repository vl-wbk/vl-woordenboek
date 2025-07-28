<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Defines authorization logic for 'blog' model actions.
 *
 * This policy class determines whether a given user can perform specific actions on blog posts, such as submitting, viewing, updating, publishing, or deleting them, as well as commenting.
 * It leverages Laravel's authorization system to return `Response` objects indicating `allow` or `deny` access.
 *
 * @package App\Policues
 */
final readonly class BlogPolicy
{
    /**
     * Determine whether the user can perform any action on blog posts.
     *
     * This "before" method is executed before any other policy method.
     * If it returns a non-null `Response`, that response will short-circuit the authorization check.
     * Administrators and Developers are granted full access.
     *
     * @param  User $user       The authenticated user attempting the action.
     * @return Response|null    A `Response::allow()` if the user is an administrator or developer, otherwise `null` to proceed to other policy methods.
     */
    public function before(User $user): ?Response
    {
        return ($user->isAdministrator() || $user->isDeveloper())
            ? Response::allow()
            : null;
    }

    public function submitPost(User $user): Response
    {
        return $user->hasVerifiedEmail()
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function viewAny(User $user): Response
    {
        return Response::denyAsNotFound();
    }

    public function view(User $user, Blog $blog): Response
    {
        return Response::denyAsNotFound();
    }

    /**
     * @todo Document
     * @deprecated - rename canComment to writeComment
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
