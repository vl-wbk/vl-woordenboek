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
            : Response::deny(message: 'u hebt geen toestemming om de lijst met woorden van de dag te bekijken.');
    }

    public function view(User $user, WordOfTheDay $wordOfTheDay): Response
    {
        return $user->can('woorden-van-de-dag')
            ? Response::allow()
            : Response::deny(message: 'u hebt geen toestemming om de plannings gegevens van het woord te bekijken.');
    }

    public function update(User $user, WordOfTheDay $wordOfTheDay): Response
    {
        return (! $wordOfTheDay->scheduled_for->isToday() && $user->can('woorden-van-de-dag'))
            ? Response::allow()
            : Response::deny(message: 'U hebt geen toestemming om de planning van het woord te wijzigen.');
    }

    public function delete(User $user, WordOfTheDay $wordOfTheDay): Response
    {
        return (! $wordOfTheDay->scheduled_for->isToday() && $user->can('woorden-van-de-dag'))
            ? Response::allow()
            : Response::deny(message: 'U hebt geen toestemming om de planning van het woord te verwijderen.');
    }
}
