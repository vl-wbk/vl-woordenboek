<?php

declare(strict_types=1);

namespace App\Models\Relations;

use App\Models\Region;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * @param  array<int, string>  $regions
     */
    public function syncRegions(array $regions): void
    {
        $this->regions()->sync($regions);
    }
}
