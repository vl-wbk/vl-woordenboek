<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Feedback;
use App\Models\User;

/**
 * @todo document policy class
 */
final readonly class FeedbackPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_feedback');
    }

    public function view(User $user, Feedback $feedback): bool
    {
        return $user->can('view_feedback');
    }

    public function delete(User $user): bool
    {
        return $user->can('delete_feedback');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_feedback');
    }
}
