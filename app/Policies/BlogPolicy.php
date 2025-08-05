<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final readonly class BlogPolicy
{
    public function before(User $user): ?Response
    {
        if ($user->cannot('page_Blog')) {
            return Response::deny(
                message: __('authorization.policies.responses.deny_before_message', replace: [
                    'resource' => __('authorization.resources.blogPosts'),
                ]),
            );
        }

        return null;
    }

    public function submitPost(User $user): Response
    {
        if ($user->hasVerifiedEmail()) {
            return Response::allow();
        }

        return Response::deny(message: __('authorization.policies.responses.deny_submit_post_message'));
    }

    public function viewAny(User $user): Response
    {
        if ($user->can('view_any_blog')) {
            return Response::allow();
        }

        return Response::denyAsNotFound(
            message: __('authorization.policies.responses.deny_view_any_message', replace: [
                'resource' => __('authorization.resources.blogPosts'),
            ]),
        );
    }

    public function view(User $user, Blog $blog): Response
    {
        if ($user->can('view_blog')) {
            return Response::allow();
        }

        return Response::denyAsNotFound(
            message: __('authorization.policies.messages.deny_view_message', replace: [
                'resource' => __('authorization.resources.blogPost'),
            ]),
        );
    }

    public function canComment(User $user, Blog $blog): Response
    {
        // TODO: Maybe its an idea if we register here a permission for placing reactions on indivual user acounts.
        if ($blog->comments_enabled && $user->hasVerifiedEmail()) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_create_comment_message', replace: [
                'resource' => __('authorization.resources.blogPost')
            ]),
        );
    }

    public function update(User $user, Blog $blog): Response
    {
        if ($blog->author()->is($user) || $user->can('update_blog')) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_update_message', replace: [
                'resource' => __('authorization.resources.blogPost'),
            ]),
        );
    }

    public function publish(User $user, Blog $blog): Response
    {
        // FIXME: Investigate why there is no permission declaration nor usage in this policy method.
        if ($blog->author()->is($user)) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.poliies.responses.deny_publication_message', replace: [
                'resource' => __('authorization.resources.blogPost')
            ]),
        );
    }

    public function delete(User $user, Blog $blog): Response
    {
        if ($blog->author->is($user) || $user->can('delete_blog')) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_delete_message', replace: [
                'resource' => __('authorization.resources.blogPost')
            ]),
        );
    }

    public function undoPublication(User $user, Blog $blog): Response
    {
        if ($blog->author()->is($user) || $user->can('undo_publication_blog')) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_undo_publication_message', replace: [
                'resource' => __('authorization.resources.blogPosts')
            ]),
        );
    }

    public function deleteAny(User $user): Response
    {
        if ($user->can('delete_any_blog')) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_delete_any_messages', replace: [
                'resource' => __('authorization.resources.blogPosts'),
            ]),
        );
    }
}
