<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\RegionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Welcome to the Region model - our way of mapping the rich tapestry of Flemish dialects
 * across different geographical areas.
 *
 * This model helps us understand where specific words and expressions come from.
 * Think of it as a digital map that connects linguistic features to their geographical origins.
 * Whether a word is typical for West Flanders or unique to the Kempen region,
 * this is where we keep track of those geographical connections.
 *
 * We keep things simple here each region has a name, and it can be connected to various linguistic features through a flexible polymorphic relationship.
 * This means we can link regions not just to words, but to any linguistic feature we might want to track in the future.
 *
 * @property int             $id            The unique identifier for the geographical region in the database.
 * @property string          $name          The name for the geographical region
 * @property Carbon|null     $created_at    Timestamp that indicates when the region has been created
 * @property Carbon|null     $updated_at    Timestamp that indicates when the region has been updated for the last time.
 *
 * @package App\Models
 */
final class Region extends Model
{
    /**
     * Including the factory for testing purposes.
     * This lets us create test regions quickly and consistently when we're making sure everything works as it should.
     *
     * @use HasFactory<RegionFactory>
     */
    use HasFactory;

    /**
     * The attributes that can be mass-assigned.
     * We only allow the region name to be filled directly.
     * This keeps our data clean and secure by preventing unwanted attributes from sneaking in.
     *
     * @var list<string>
     */
    protected $fillable = ['id', 'name'];
}
