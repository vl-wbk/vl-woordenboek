<?php

declare(strict_types=1);

namespace App\Models\Relations;

use App\Models\Region;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @todo document this trait
 */
trait BelongsToManyRegions
{
    /**
     * @todo document this method
     * @return BelongsToMany<Region, covariant $this>
     */
    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class);
    }

    /**
     * @todo Document this method
     * @param  array<int, string> $regions
     */
    public function syncRegions(array $regions): void
    {
        $this->regions()->sync($regions);
    }
}
