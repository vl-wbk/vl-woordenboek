<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Welcome to the Region model - our way of mapping the rich tapestry of Flemish dialects
 * across different geographical areas.
 *
 * This model helps us understand where specific words and expressions come from.
 * Think of it as a digital map that connects linguistic features to their geographical origins.
 * Whether a word is typical for West-Vlaanderen or unique to the Kempen region,
 * this is where we keep track of those geographical connections.
 *
 * We keep things simple here each region has a name, and it can be connected to various linguistic features through a flexible polymorphic relationship.
 * This means we can link regions not just to words, but to any linguistic feature we might want to track in the future.
 *
 * @property int             $id            The unique identifier for the geographical region in the tabase.
 * @property string          $name          The nbame for the geographical region
 * @property \Carbon\Carbon  $created_at    Timestamp that indicates when the region has been created
 * @property \Carbon\Carbon  $updated_at    Timestamp that indicates when the region has been updated for the last time.
 *
 * @package App\Models
 */
final class Region extends Model
{
    /**
     * Including the factory for testing purposes.
     * This lets us create test regions quickly and consistently when we're making sure everything works as it should.
     *
     * @use HasFactory<\Database\Factories\RegionFactory>
     */
    use HasFactory;

    /**
     * The attributes that can be mâss-assigned.
     * We only allow the region name to be filled directly.
     * This keeps our data clean and secure by preventing unwanted attributes from sneaking in.
     *
     * @var list<string>
     */
    protected $fillable = ['id', 'name'];

    /**
     * The linguistic connection - where geography meets language.
     *
     * This relationship is the heart of our regional tracking system.
     * It creates a flexible connection between regions and various linguistic features.
     * Using a polymorphic relationship means we're not limited to just one type of linguistic feature.
     * A  region could be connected to words, expressions, or any other linguistic element we decide to track.
     *
     * @return MorphTo<\Illuminate\Database\Eloquent\Model, covariant $this>
     */
    public function linguistic(): MorphTo
    {
        return $this->morphTo();
    }
}
