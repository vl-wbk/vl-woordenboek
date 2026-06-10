<?php

declare(strict_types=1);

namespace App\Console\Commands\Users;

use App\Concerns\HandlesDatabaseTransactions;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('vl-wbk:apply-reputation-decay')]
#[Description('Apply reputation decay to inactive users')]
final class ApplyReputationDecay extends Command
{
    use HandlesDatabaseTransactions;

    /**
     * Users within this windows are exampt from decay entirely.
     * Gives recently inactive users a fair chance to return before penalising them.
     */
    private const GRACE_PERIOD_DAYS = 14;

    /**
     * Number of users processed per DB transaction.
     * Keeping this small limits lock contention and ensures a single bad chunk
     * doesn't block or roll back work done on previous ones.
     */
    private const CHUNK_SIZE = 50;

    /**
     * Decay tiers ordered from least to most severe.
     *
     * Each tier defines:
     *   - max_days:  upper bound of inactivity (days) this tier applies to
     *   - rate:      fraction of reputation deducted per cycle (e.g. 0.03 = 3%)
     *   - frequency: minimum days that must pass between decay cycles (null = no decay)
     *
     * The tiers must stay in ascending max_days order — getDecayConfig() returns
     * the first tier whose max_days is >= the user's inactivity, so ordering matters.
     */
    private const DECAY_TIERS = [
        ['max_days' => 14, 'rate' => 0.00, 'frequency' => null],
        ['max_days' => 30, 'rate' => 0.01, 'frequency' => 7],
        ['max_days' => 60, 'rate' => 0.03, 'frequency' => 7],
        ['max_days' => INF, 'rate' => 0.05, 'frequency' => 3],
    ];

    /**
     * Process eligible users in chunks, applying reputation decay to each.
     *
     * Chunking is used over a full collection load to keep memory flat regardless
     * of how many users qualify. Each chunk is wrapped in its own transaction so a
     * failure mid-chunk only rolls back that chunk, not the entire job.
     */
    public function handle(): void
    {
        $this->fetchEligibleUsers()->chunkById(self::CHUNK_SIZE, function ($users): void {
            // The transaction wraps each chunk intentionally — if one chunk fails,
            // only that chunk rolls back rather than undoing work on previous chunks.
            $this->executeInTransaction(fn () => $users->each($this->applyDecay(...)));

            sleep(1); // Small pause between chunks to ease DB load
        });

        $this->info('Reputation decay applied.');
    }

    /**
     * Scope down to users who are both past the grace period and have reputation to lose.
     *
     * The +1 on the grace period converts the "greater than 14 days" intent into a
     * "<= 15 days ago" date comparison, since diffInDays truncates rather than rounds.
     */
    private function fetchEligibleUsers()
    {
        return User::where('last_seen_at', '<=', now()->subDays(self::GRACE_PERIOD_DAYS + 1))
            ->where('reputation', '>', 0);
    }

    /**
     * Resolve the decay rate and minimum cycle frequency for a given inactivity duration.
     *
     * Walks DECAY_TIERS in order and returns the first tier that covers $daysInactive.
     * A rate of 0.0 or a null frequency means no decay should be applied — callers are
     * expected to check for this before proceeding.
     *
     * @return array{ rate: float, frequency: int|null }
     */
    public function getDecayConfig(int $daysInactive): array
    {
        foreach (self::DECAY_TIERS as $tier) {
            if ($daysInactive <= $tier['max_days']) {
                return ['rate' => $tier['rate'], 'frequency' => $tier['frequency']];
            }
        }
    }

    /**
     * Deduct a reputation penalty from a single user if they are due for a decay cycle.
     *
     * The penalty is a percentage of current reputation (floored to an integer) with a
     * minimum of 1 point, so even users near zero always feel some pressure to return.
     * Points are deducted via subtractPoints() which handles the audit trail.
     *
     * A decay cycle is skipped when:
     *   - last_seen_at is missing (can't determine inactivity)
     *   - the user's tier carries no decay (grace period or rate of 0)
     *   - not enough days have passed since the last decay cycle
     *
     * last_decayed_at falls back to daysInactive when null so a user who has never
     * been decayed is treated as immediately eligible once their tier requires it.
     */
    public function applyDecay(User $user): void
    {
        if (! $user->last_seen_at) {
            return;
        }

        $daysInactive = (int) $user->last_seen_at->diffInDays(now());
        $config = $this->getDecayConfig($daysInactive);

        if ($config['rate'] === 0.0 || $config['frequency'] === null) {
            return;
        }

        $daysSinceLastDecay = $user->last_decayed_at
            ? (int) $user->last_decayed_at->diffInDays(now())
            : $daysInactive;

        if ($daysSinceLastDecay < $config['frequency']) {
            return;
        }

        $penalty = max((int) floor($user->reputation * $config['rate']), 1);

        $user->subtractPoints(points: $penalty, reason: 'Verlies van reputatie wegens inactiviteit');
        $user->update(['last_decayed_at' => now()]);
    }
}
