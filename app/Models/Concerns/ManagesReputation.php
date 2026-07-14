<?php

declare(strict_types=1);


namespace App\Models\Concerns;

use App\Models\ReputationLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Provides a reputation system for the consuming model (typically the User model).
 *
 * Reputation is a cumulative integer score that unlocks progressively more privileged actions
 * as the user crosses predefined thresholds. Each threshold defines a human-readable level label
 * and the set of action strings that become available upon reaching it.
 *
 * The trait assumes the consuming model has:
 * - a 'reputation' integer column on its database table.
 * - a 'reputationLogs' relationship resolvable via the ReputationLog model.
 *
 * @see User
 * @see ReputationLog
 *
 * @property int $reputation The model's current reputation score.
 *
 * @package App\Models\Concerns
 */
trait ManagesReputation
{
    /**
     * The reputation ladder: each entry defines a level name, the minimum scord the reach, and which actions it unlocks.
     *
     * To add a new privilege, add its action string to the appropriate level's actions array.
     * The same string must then be passed to the canPerform() wherever the permission is checked.
     * Keep thresholds in ascending order.
     *
     * @see canPerform()
     *
     * @var array<int, array{label: string, threshold: int, actions: string[]}>
     */
    protected static array $reputationThresholds = [
        ['label' => 'Zoeker',           'threshold' => 0,    'actions' => []],
        ['label' => 'Lezer',            'threshold' => 100,  'actions' => []],
        ['label' => 'Schrijver',        'threshold' => 500,  'actions' => ['artikel beschrijvingen bewerken']],
        ['label' => 'Taalliefhebber',   'threshold' => 1000, 'actions' => []],
        ['label' => 'Woordkunstenaar',  'threshold' => 2000, 'actions' => []],
        ['label' => 'Ambassadeur',      'threshold' => 4000, 'actions' => []],
        ['label' => 'Veteraan',         'threshold' => 8000, 'actions' => []],
    ];

    /**
     * All reputation change records for this model.
     *
     * Every call to awardPoints() or substractPoints() appends a row her,
     * giving you a full audit trail of why the score is changed.
     *
     * @see awardPoints()
     * @see subtractPoints()
     *
     * @return HasMany<ReputationLog, covariant $this>
     */
    public function reputationLogs(): HasMany
    {
        return $this->hasMany(ReputationLog::class);
    }

    /**
     * Add points to the model's reputation and log why.
     *
     * Prefer descriptive, human-readable reasons so the log is usefuk when displayed in a UI
     * or reviewed by an admin for example "Correctie van een artikel beschrijving"rather than "edit".
     *
     *
     * @param  int      $points     How many points to add. Passing 0 is a no-op but still writes a log entry.
     * @param  string   $reason     A short description of why the points were awarded.
     * @return void
     */
    public function awardPoints(int $points = 0, string $reason = 'submission_approved'): void
    {
        $this->increment('reputation', $points);
        $this->reputationLogs()->create(['points' => $points, 'reason' => $reason]);
    }

    /**
     * Remove points from the model's reputation and log why.
     *
     * ? Note: the 'points' value stored in the log is always positive, The direction
     * ? (deduction vs. award) is implied by which method called, not by the sign or number.
     *
     * Be careful not to let reputation go negative unless your UI handle that gracefully.
     * You may want to add a floor of 0 here in the future.
     *
     * @param  int      $points     How many points to remove. Passing 0 is a no-op but still writes a log entry.
     * @param  string   $reason     A short description of why the points were deducted.
     * @return void
     */
    public function subtractPoints(int $points = 0, string $reason = 'submission_invalidated'): void
    {
        $points = abs($points);
        $amountToDecrement = min($this->reputation, $points);

        if ($amountToDecrement > 0) {
            $this->decrement('reputation', $amountToDecrement);
            $this->reputationLogs()->create(['points' => $points, 'reason' => $reason]);
        }
    }

    /**
     * Return the full threshold ladder.
     * Useful when you need to render the complete level list in a UI,  for example "how does reputation work?" help page.
     *
     * @return array{actions: array, label: string, threshold: int[]}
     */
    public function reputationThresholds(): array
    {
        return self::$reputationThresholds;
    }

    /**
     * Resolves the highest level label the model has reached.
     *
     * This works by walking all thresholds in order and overwriting the result each time the model qualifies,
     * so the last matching threshold always wins. If the score is somehow every threshold (shouldn't happen with a 0 baseline),
     * it falls back to "Zoeker".
     *
     * @return string The level label, e.g. "schrijver"
     */
    public function reputationLevel(): string
    {
        $level = 'Zoeker';

        foreach (self::$reputationThresholds as $r) {
            if ($this->reputation >= $r['threshold']) {
                $level = $r['label'];
            }
        }

        return $level;
    }

    /**
     * Whether the model has reached expert status (2 000+ reputation).
     *
     * Several other methods use this as a shortcut to skip further calculation once the top
     * of the tracked progress range is reached. If you raise the expert threshold, update those methods too.
     *
     * @return bool True if reputation is 2000 or above.
     */
    public function isExpert(): bool
    {
        return $this->reputation >= max(array_column(self::$reputationThresholds, 'threshold'));
    }

    /**
     * Percentage progress towards the next threshold, intended for progress bars.
     *
     * Returns 100 once the model is an expert. For everyone else, the percentage is calculated
     * against the nearest upcoming threshold in the range {0, 100, 500, 1000, 2000, 4000, 8000}.
     *
     * ! Heads up: this method currently hardcodes those values rather than deriving them from $reputationThresholds.
     * ! If you add or change threshold values, remember to update this method too, or it will silently return wrong numbers.
     *
     * @return int A whole number between 0 and 100.
     */
    public function reputationProgress(): int
    {
        if ($this->isExpert()) {
            return 100;
        }

        $thresholds = array_column(self::$reputationThresholds, 'threshold');
        $current = $this->reputation;

        $next = collect($thresholds)->first(fn ($t) => $t > $current);

        return (int) (($current / $next) * 100);
    }

    /**
     * How many more points the model needs to reach the next level.
     * Returns 0 for experts - they are already at the top of the tracked range.
     *
     * ! Same caveat as reputationProgress(): the next-level thresholds are hardcoded here.
     * ! If you extend the ladder, update this method as well.
     *
     * @see reputationProgress()
     *
     * @return int Points remaining. always > 0.
     */
    public function reputationToNextLevel(): int
    {
        if ($this->isExpert()) {
            return 0;
        }

        $thresholds = array_column(self::$reputationThresholds, 'threshold');
        $next = collect($thresholds)->first(fn ($t) => $t > $this->reputation);

        return max(0, $next - $this->reputation);
    }

    /**
     * All actions the model is currently allowed to perform.
     *
     * Walks every reached threshold and merges their action lists into one flat array.
     * Use this when you need to display a "Your current privileges" summary.
     * For a single yes/no permission check, see canPerform() is more direct.
     *
     * @see canPerform()
     *
     * @return string[] Flat list of unlocked action strings, may be empty.
     */
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
     * All actions the model cannot yet perform, along with the score needed to unlock each.
     *
     * Useful for building a "here is what you can unlock next" UI.
     * Each entry tells you the action name and the exact threshold required, so you can show
     * the user something like "500 pts needed to unlock article editing".
     *
     * @return list<array{action: string, threshold: int}>
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

    /**
     * Check whether the model is allowed to perform a specific action.
     *
     * This is your go-to for a single permission gate — for example, before
     * letting a user edit an article description:
     *
     *   if (! $user->canPerform('artikel beschrijvingen bewerken')) {
     *       abort(403);
     *   }
     *
     * The action string must exactly match what is defined in {@see $reputationThresholds}.
     * A typo here will silently return false, so consider extracting action
     * strings to constants or an enum if the list grows.
     *
     * @param  string  $actionName  The action to check, e.g. 'artikel beschrijvingen bewerken'.
     * @return bool True if the model's reputation qualifies it for this action.
     */
    public function canPerform(string $actionName): bool
    {
        foreach (self::$reputationThresholds as $level) {
            if ($this->reputation >= $level['threshold'] && in_array($actionName, $level['actions'])) {
                return true;
            }
        }

        return false;
    }
}
