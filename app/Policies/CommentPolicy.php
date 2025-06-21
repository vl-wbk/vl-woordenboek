<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;
use App\UserTypes;
use Illuminate\Auth\Access\Response;

final readonly class CommentPolicy
{
    public function delete(User $user, Comment $comment): Response
    {
        if ($comment->commentator->is($user) || $user->user_type->in(enums: [UserTypes::Developer, UserTypes::Administrators])) {
            return Response::allow();
        }

        return Response::denyAsNotFound();
    }
}
