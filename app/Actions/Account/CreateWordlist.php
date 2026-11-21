<?php

declare(strict_types=1);

namespace App\Actions\Account;

use App\Concerns\HandlesDatabaseTransactions;
use App\Exceptions\DatabaseTransactionException;
use App\Models\{User, WordList};
use Throwable;

final class CreateWordlist
{
    use HandlesDatabaseTransactions;

    /**
     * @throws DatabaseTransactionException
     * @throws Throwable
     */
    public function __invoke(User $user, array $data): WordList
    {
        return $this->executeInTransaction(
            callback: fn(): WordList => $user->wordLists()->create($data)
        );
    }
}
