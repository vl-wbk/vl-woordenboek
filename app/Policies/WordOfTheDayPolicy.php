<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\WordOfTheDay;
use Illuminate\Auth\Access\Response;

final readonly class WordOfTheDayPolicy
{
    public function viewAny(User $user): Response
    {
        return $user->can('woorden-van-de-dag')
            ? Response::allow()
            : Response::deny();
    }

    public function view(User $user, WordOfTheDay $wordOfTheDay): Response
    {
        return $user->can('woorden-van-de-dag')
            ? Response::allow()
            : Response::deny();
    }

    public function update(User $user, WordOfTheDay $wordOfTheDay): Response
    {
        return (! $wordOfTheDay->scheduled_for->isToday() && $user->can('woorden-van-de-dag'))
            ? Response::allow()
            : Response::deny();
    }

    public function delete(User $user, WordOfTheDay $wordOfTheDay): Response
    {
        return (! $wordOfTheDay->scheduled_for->isToday() && $user->can('woorden-van-de-dag'))
            ? Response::allow()
            : Response::deny();
    }
}
