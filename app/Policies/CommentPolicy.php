<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;
use App\UserTypes;
use Illuminate\Auth\Access\Response;

final readonly class CommentPolicy
{
    /**
     * @todo Write a permission prolicy for this one.
     */
    public function delete(User $user, Comment $comment): Response
    {
        if ($comment->commentator->is($user) || $user->user_type->in(enums: [UserTypes::Developer, UserTypes::Administrators])) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses..deny_delete_message', replace: [
                'resource' => __('authorization.resource.comment'),
            ]),
        );
    }
}
