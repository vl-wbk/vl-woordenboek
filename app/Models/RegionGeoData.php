<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The RegionGeoData model represents geographical data associated with regions.
 *
 * The model stores geographic information such as boundaries, coordinates, and other spatial data that defines the physical area of a region.
 * It maintains a relationship with the Region model, allowing geographical data to be associated with specific regions in the system.?
 *
 * @property int          $id                   The unique identifier for this geographical data.
 * @property ?int         $region_id            The unique identifier of the associated region
 * @property string       $postal               The postal code this geographical area
 * @property string       $geometry_geojson     The geometric data defining the area boundaries
 * @property ?string      $name                 An optional name for this geographical area
 * @property Carbon|null  $created_at           Timestamp of when this record was created
 * @property Carbon|null  $updated_at           Timestamp of when this record was last updated
 *
 * @package App\Models
 */
#[Guarded('id')]
final class RegionGeoData extends Model
{
    /**
     * Defines the relationship between geographical data and its associated region.
     *
     * Each geographical data entry belongs to exactly one region.
     * This relationship allows us to retrieve the region associated with specific geographical data, enabling features like region visualization and boundary detection.
     *
     * @return BelongsTo<Region, covariant $this> The relationship instance connecting to the region model.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
