<?php

declare(strict_types=1);


namespace App\Models\Concerns;

use App\Models\ReputationLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait ManagesReputation
{
    // 1. Current Level Name
    protected static array $reputationThresholds = [
        ['label' => 'Zoeker',    'threshold' => 0,    'actions' => []],
        ['label' => 'Lezer',      'threshold' => 100,  'actions' => []],
        ['label' => 'Schrijver', 'threshold' => 500,  'actions' => ['artikel beschrijvingen bewerken']], //! Change to 500 when ready, 20 is only to test things
        ['label' => 'Taalliefhebber',      'threshold' => 1000, 'actions' => []],
        ['label' => 'Woordkunstenaar',      'threshold' => 2000, 'actions' => []],
        ['label' => 'Ambassadeur',      'threshold' => 4000, 'actions' => []],
        ['label' => 'Veteraan',      'threshold' => 8000, 'actions' => []],
    ];

    public function reputationLogs(): HasMany
    {
        return $this->hasMany(ReputationLog::class);
    }

    public function awardPoints(int $points = 0, string $reason = 'submission_approved'): void
{
    $this->increment('reputation', $points);
    $this->reputationLogs()->create(['points' => $points, 'reason' => $reason]);
}

public function subtractPoints(int $points = 0, string $reason = 'submission_invalidated'): void
{
    $this->decrement('reputation', $points);
    $this->reputationLogs()->create(['points' => $points, 'reason' => $reason]);
}


    public function reputationThresholds(): array
    {
        return self::$reputationThresholds;
    }

    public function reputationLevel(): string
    {
        $level = 'Newcomer';
        foreach (self::$reputationThresholds as $r) {
            if ($this->reputation >= $r['threshold']) $level = $r['label'];
        }
        return $level;
    }

    // 2. Expert Check
    public function isExpert(): bool
    {
        return $this->reputation >= 1000;
    }

    // 3. Progress Logic (for the progress bar)
    public function reputationProgress(): int
    {
        if ($this->isExpert()) return 100;

        $thresholds = [0, 100, 500, 1000];
        $current = $this->reputation;

        // Find the range the user is currently in
        $next = 1000;
        foreach ($thresholds as $t) {
            if ($t > $current) {
                $next = $t;
                break;
            }
        }

        // Simple percentage calculation
        return (int) (($current / $next) * 100);
    }

    // 4. Points needed for next level
    public function reputationToNextLevel(): int
    {
        if ($this->isExpert()) return 0;

        $next = ($this->reputation >= 500) ? 1000 : (($this->reputation >= 100) ? 500 : 100);
        return max(0, $next - $this->reputation);
    }

    public function availableActions(): array
{
    $unlocked = [];
    foreach (self::$reputationThresholds as $level) {
        if ($this->reputation >= $level['threshold']) {
            $unlocked = array_merge($unlocked, $level['actions']);
        }
    }

    return $unlocked;
}

/**
 * Get all actions the user hasn't unlocked yet.
 */
public function unavailableActions(): array
{
    $unavailable = [];
    foreach (self::$reputationThresholds as $level) {
        if ($this->reputation < $level['threshold']) {
            foreach ($level['actions'] as $action) {
                $unavailable[] = [
                    'action' => $action,
                    'threshold' => $level['threshold']
                ];
            }
        }
    }
    return $unavailable;
}

    public function canPerform(string $actionName): bool
    {
        foreach (self::$reputationThresholds as $level) {
        // If user meets the threshold, check if the action is in this level's array
        if ($this->reputation >= $level['threshold'] && in_array($actionName, $level['actions'])) {
            return true;
        }
    }
    return false;
    }
}
