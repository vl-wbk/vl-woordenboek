<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use BeyondCode\Comments\Comment;
use Illuminate\Auth\Access\Response;

final readonly class CommentPolicy
{
    public function delete(User $user, Comment $comment): Response
    {
        if ($comment->commentator->is($user)) {
            return Response::allow();
        }

        return Response::denyAsNotFound();
    }
}
