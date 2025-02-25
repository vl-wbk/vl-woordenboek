<?php

declare(strict_types=1);

namespace App\Models\Relations;

use App\Models\Region;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait BelongsToManyRegions
{
    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class);
    }

    public function syncRegions(array $regions): void
    {
        $this->regions()->sync($regions);
    }
}
