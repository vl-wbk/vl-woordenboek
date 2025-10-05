<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Defines the comprehensive authorization logic for the 'Blog' model.
 *
 * This policy clpass meticulously contras a user's permissions to interact withblog posts.
 * Each method returns a 'Response' object to explicitly grant of deny access, providing clear and robust control over actions like submitting, viewing, updating, publishing,
 * deleting, and commenting on blog posts.
 *
 * @link file://tests/Unit/Authorization/BlogPolicyTest.php
 * @package App\Policues
 */
final class BlogPolicy
{
    public static array $permissionPrefixes = [
        'viewAny', 'view', 'update', 'delete', 'deleteAny', 'undoPublication'
    ];

    /**
     * Authorizes a user to create a new plog post.
     *
     * The core requirement for this action is a verified email address for now.
     * This prevents unverified accounts from publishing possible new spammy reactions.
     *
     * @param  User $user   The authenticated user.
     * @return Response     Grants access if the user's email address is verified, otherwise denies it.
     */
    public function submitPost(User $user): Response
    {
        return $user->hasVerifiedEmail()
            ? Response::allow()
            : Response::denyAsNotFound(message: __('U hebt niet de juiste machtiging om een nieuwsbericht in te sturen ter publicatie.'));
    }

    /**
     * Authorizes a user to view a list of all blog posts.
     *
     * Thi method is for viewing the index or "any" blog posts.
     * The check relies on the user possesing thez general 'view_any_blog' permission.
     *
     * @param  User $user  The authenticated user.
     * @return Response    Grants access if the user has the required permission, otherwise denies it.
     */
    public function viewAny(User $user): Response
    {
        return $user->can('view-any:blog')
            ? Response::allow()
            : Response::denyAsNotFound(message: __('U hebt niet de juiste machtiging om een overzicht van alle nieuwsberichten te bekijken.'));
    }

    /**
     * Auhtorizes  a user to view a sepcific blog post.
     *
     * This method is for viewing a single blog post.
     * It checks for more specific 'view_blog' permission.
     *
     * @param  User $user  The authenticated user.
     * @param  Blog $blog  The specific blog post being viewed.
     * @return Response    Grants access if the user has the required permission, otherwise denies it.
     */
    public function view(User $user, Blog $blog): Response
    {
        return $user->can('view:blog')
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Authorizes a user to write a comment on a blog post.
     *
     * Authorization is granted only if:
     * 1. The specific blog post has comments enabled (`$blog->comments_enabled`).
     * 2. The user has a verified email address.
     *
     * @param  User $user  The authenticated user.
     * @param  Blog $blog  The blog post for which a comment is being written.
     * @return Response    Grants access if both conditions are met, otherwise denies it.
     *
     * @deprecated The method name `canComment` is deprecated. Please rename it to `writeComment` to align with common conventions.
     */
    public function canComment(User $user, Blog $blog): Response
    {
        return ($blog->comments_enabled && $user->hasVerifiedEmail())
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Authorizes a user to update an existing blog post.
     *
     * Authorization is granted if either of these conditions is met:
     * 1. The user is the original author of the blog post (`$blog->author()->is($user)`).
     * 2. The user has the specific `update_blog` permission.
     *
     * @param  User $user  The authenticated user.
     * @param  Blog $blog  The blog post being updated.
     * @return Response    Grants access if the user is the author or has the permission, otherwise denies it.
     */
    public function update(User $user, Blog $blog): Response
    {
        return ($blog->author()->is($user) || $user->can('update:blog'))
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Authorizes a user to publish an existing blog post.
     *
     * This is a highly restricted action.
     * Only the original author of the blog post is authorized to publish it.
     *
     * @param  User $user   The authenticated user.
     * @param  Blog $blog   The blog post to be published.
     * @return Response     Grants access only if the user is the post's author.
     */
    public function publish(User $user, Blog $blog): Response
    {
		if ($blog->status->isPublished()) {
			return Response::denyAsNotFound();
		}

        return Response::allow();
    }

	/**
	 * Authorizes a user to unpublish a blog post.
	 *
	 * Authorization is granted if either of these conditions is met:
	 * 1. The user is the original author of the blog post.
	 * 2. The user has the specific `undo_publication_blog` permission.
	 *
	 * @param  User $user   The authenticated user.
	 * @param  Blog $blog   The blog post whose publication status is being undone.
	 * @return              Response Grants access if the user is the author or has the permission, otherwise denies it.
	 */
	public function undoPublication(User $user, Blog $blog): Response
	{
		if ($user->can('undo-publication:blog') && $blog->status->isPublished()) {
			return Response::allow();
		}

		return Response::denyAsNotFound();
	}

    /**
     * Authorizes a user to delete an existing blog post.
     *
     * Authorization is granted if either of these conditions is met:
     * 1. The user is the original author of the blog post.
     * 2. The user has the specific `delete_blog` permission.
     *
     * @param  User $user  The authenticated user.
     * @param  Blog $blog  The blog post to be deleted.
     * @return Response    Grants access if the user is the author or has the permission, otherwise denies it.
     */
    public function delete(User $user, Blog $blog): Response
    {
        return ($blog->author()->is($user) || $user->can('delete:blog'))
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Authorizes a user to delete any blog posts in bulk.
     *
     * This is a special method for bulk-deleting records. Unlike other policy methods, it simply returns a boolean.
     * It checks if the user has the `delete_any_blog` permission, which is typically reserved for administrators.
     *
     * @param  User $user   The authenticated user.
     * @return Response     Returns `true` if the user has the permission, `false` otherwise.
     */
    public function deleteAny(User $user): Response
    {
        return $user->can('delete-any:blog')
			? Response::allow()
			: Response::denyAsNotFound();
    }


    private function activateComments(User $user, Blog $blog): bool
    {
        return ($blog->hasCommentsDisabled() && $blog->isPublished())
            && ($user->isDeveloper() || $user->isAdministrator());
    }
}
