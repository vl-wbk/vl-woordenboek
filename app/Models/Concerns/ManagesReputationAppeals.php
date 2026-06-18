<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Appeal;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait ManagesReputationAppeals
{
    public function appeals(): HasMany
    {
        return $this->hasMany(Appeal::class);
    }

    public function monthlyAppeals(): Attribute
    {
        return Attribute::make(
            get: fn (): int => $this->appeals()
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->count()
        );
    }
}
