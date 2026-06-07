<?php 

declare(strict_types=1); 

namespace App\Actions\Account;

use App\Concerns\HandlesDatabaseTransactions;
use App\Models\User;

final readonly class UpdateTrustScore
{
    use HandlesDatabaseTransactions; 

    public function __invoke(User $user, float $amount): void 
    {
        $score = max(0, $user->trust_score + $amount);

        $this->executeInTransaction(
            callback: fn () => $user->updateQuietly(['trust_score' => $score])
        );
    }
}