<?php

declare(strict_types=1);

namespace App\Actions\Account\Reputation;

use App\Concerns\HandlesDatabaseTransactions;
use App\Data\AppealData;
use App\Models\Appeal;
use App\Models\User;

final readonly class RegisterAppeal
{
    use HandlesDatabaseTransactions;

    public function __invoke(User $user, AppealData $appealData): void
    {
	    $this->executeInTransaction(
			callback: fn (): Appeal => $user->appeals()->create([
                'reputation_log_id' => $appealData->reputation_log_id,
                'reason'            => $appealData->reason,
                'status'            => 'pending',
            ])
		);
    }
}
