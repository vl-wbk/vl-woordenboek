<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\{User, WordList};
use Illuminate\Auth\Access\Response;

final class WordListPolicy
{
    public function update(User $user, WordList $wordlist): Response
    {
        return ($wordlist->user()->is($user))
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function view(User $user, WordList $wordlist): Response
    {
        return $wordlist->user()->is($user)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function delete(User $user, WordList $wordlist): Response
    {
        return ($wordlist->user()->is($user))
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
