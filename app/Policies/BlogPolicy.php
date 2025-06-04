<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Blog;
use App\Models\User;

final readonly class BlogPolicy
{
    /**
     * @todo rename canComment to writeComment
     * @deprecated
     */
    public function canComment(User $user, Blog $blog): bool
    {
        return $blog->comments_enabled && $user->hasVerifiedEmail();
    }
}
