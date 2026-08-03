<?php

declare(strict_types=1);

namespace App\Actions\Account;

use App\Concerns\HandlesDatabaseTransactions;
use App\Models\{User, WordList};

final class CreateWordlist
{
    use HandlesDatabaseTransactions;

    public function __invoke(User $user, array $data): WordList
    {
        return $this->executeInTransaction(
            callback: fn(): WordList => $user->wordLists()->create($data)
        );
    }
}
