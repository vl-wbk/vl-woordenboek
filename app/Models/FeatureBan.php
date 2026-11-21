<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Pennant\Feature;

#[Fillable(['user_id', 'feature', 'reason', 'banned_by', 'expires_at'])]
final class FeatureBan extends Model
{
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bannedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'banned_by');
    }

    public function isActive(): bool 
    {
        return $this->expires_at === null || $this->expires_at->isFuture();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()));
    }
 
    public function scopeExpired(Builder $query): Builder
    {
        return $query->whereNotNull('expires_at')->where('expires_at', '<=', now());
    }

    protected static function booted(): void
    {
        // Set automatically on create (e.g. from Filament) if not provided.
        static::creating(function (self $ban) {
            $ban->banned_by ??= auth()->id();
        });
 
        // Ban created/updated -> force the stored Pennant value to false.
        static::saved(function (self $ban) {
            if ($ban->user) {
                Feature::for($ban->user)->deactivate($ban->feature);
            }
        });
 
        // Ban removed (unban or expiry) -> clear stored value so the resolver runs again.
        static::deleted(function (self $ban) {
            if ($ban->user) {
                Feature::for($ban->user)->forget($ban->feature);
            }
        });
    }
}
