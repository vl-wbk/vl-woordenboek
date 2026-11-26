<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ArticleStates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

/**
 * One immutable row per change of an article's `state`.
 */
final class ArticleStateTransition extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['article_id', 'from_state', 'to_state', 'actor_id', 'reason', 'context'];

    protected static function booted(): void
    {
        // An audit log is append-only.
        static::updating(static fn () => throw new LogicException('State transitions are append-only.'));
        static::deleting(static fn () => throw new LogicException('State transitions cannot be deleted.'));
    }

    protected function casts(): array
    {
        return [
            'from_state' => ArticleStates::class,
            'to_state'   => ArticleStates::class,
            'context'    => 'array',
        ];
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
