<?php

declare(strict_types=1);

namespace App\Models\Relations;

use App\Attributes\Todo;
use App\Models\Region;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Todo(message: 'provide docblock for this trait and his methods', priority: 'low')]
trait BelongsToManyRegions
{
    /**
     * @return BelongsToMany<Region, covariant $this>
     */
    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class);
    }

    /**
     * @param  array<int, string> $regions
     */
    public function syncRegions(array $regions): void
    {
        $this->regions()->sync($regions);
    }
}
