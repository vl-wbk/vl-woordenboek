<?php

declare(strict_types=1);

namespace App\Models\Relations;

use App\Models\Region;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Trait BelongsToManyRegions
 * 
 * This trait provides a standarized implementation for models that maintain a many-to-many relationship with 
 * geograhical or administrative regions. Using this trait ensures consistency across the application when
 * linking resoures (such as articles, users, etc.) to specific regions, and provides a clean API for 
 * managing those associations.
 * 
 * @package App\Models\Relations
 */
trait BelongsToManyRegions
{
    /**
     * Define the rgion relationship 
     * 
     * Estabilishes a many-to-many relationship with the region model. 
     * This allows the parent model to be associated with multiple regions and vice versa. 
     * 
     * @return BelongsToMany<Region, covariant $this> The eloquent relationship instance. 
     */
    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class);
    }

    /**
     * Synchronize associated regions
     * 
     * Updates the many-to-many pivot table so that the model is only associated with the provided 
     * list of region identifiers. Any IDs not present in the array will be detached from the model. 
     * 
     * @param  array<int, string> $regions An array of region IDs (integers or UUID strings)
     * @return void
     */
    public function syncRegions(array $regions): void
    {
        $this->regions()->sync($regions);
    }
}
