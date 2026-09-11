<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;
use App\UserTypes;
use Illuminate\Auth\Access\Response;

/**
 * Comment Policy
 *
 * This policy class is responsible for authorizing actions on the `Comment` model.
 * It defines the rules that determine whether a user can interact with comments,
 * such as deleting them.
 *
 * Key Features:
 * - Enforces authorization logic for comment-related operations.
 * - Supports fine-grained control over user permissions based on user roles and ownership.
 * - Utilizes Laravel's Authorization policies for a structured approach to permissions.
 *
 * @link file://tests/Unit/Authorization/CommentPolicyTest.php
 * @package App\Policies
 */
final readonly class CommentPolicy
{
    /**
     * **Determine if the given user can delete the comment.**
     *
     * This method checks if the authenticated user has the permission to delete a specific comment.
     * Permission is granted if:
     *
     * 1. The user is the author of the comment.
     * 2. The user holds an administrative role (Developer or Administrator).
     *
     * When permission is denied, `Response::denyAsNotFound()` is returned to obscure the existence
     * of the comment from unauthorized users, preventing potential security vulnerabilities.
     *
     * @param User    $user    The authenticated user attempting to perform the delete action.
     * @param Comment $comment The comment model instance to be deleted.
     *
     * @return Response An authorization response indicating whether the action is allowed or denied.
     */
    public function delete(User $user, Comment $comment): Response
    {
        // Check if the user is the owner of the comment OR if they have an administrator/developer role.
        return ($comment->commentator->is($user) || $user->user_type->in(enums: [UserTypes::Developer, UserTypes::Administrators]))
            ? Response::allow() // Allow the delete action if either condition is met.
            : Response::denyAsNotFound(); //! Deny the delete action and return a "Not found" response for security.
    }
}
