<?php

declare(strict_types=1);

namespace App\Models\Concerns\Logging;

use App\Enums\ArticleStates;
use App\Models\ArticleStateTransition;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

/**
 * Single, auditable entry point for changing an article's state.
 *
 * Callers pass the "why" (reason, origin) here; ArticleObserver turns it into a
 * log row inside the same transaction. A state change made any other way is still
 * logged, just without a reason.
 */
trait HasStateTransitions
{
    /** @var array{reason: ?string, via: ?string}|null */
    private ?array $pendingTransition = null;

    public function stateTransitions(): HasMany
    {
        return $this->hasMany(ArticleStateTransition::class)->latest('id');
    }

    public function latestStateTransition(): HasOne
    {
        return $this->hasOne(ArticleStateTransition::class)->latestOfMany();
    }

    /**
     * @param array<string, mixed> $attributes Extra columns written in the same single UPDATE.
     */
    public function transitionToState(ArticleStates $to, ?string $reason = null, array $attributes = [], ?string $via = null): bool
    {
        return DB::transaction(function () use ($to, $reason, $attributes, $via): bool {
            $this->pendingTransition = ['reason' => $reason, 'via' => $via];

            try {
                return $this->update(['state' => $to] + $attributes);
            } finally {
                $this->pendingTransition = null;
            }
        });
    }

    /**
     * Read by the observer.
     *
     * @return array{reason: ?string, via: ?string}
     */
    public function pullPendingTransition(): array
    {
        $pending = $this->pendingTransition ?? ['reason' => null, 'via' => null];
        $this->pendingTransition = null;

        return $pending;
    }
}
