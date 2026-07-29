<?php

declare(strict_types = 1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int     $id            The unique identifier from the entity.
 * @property ?string $abbreviation. The abbreviation of the reference work.
 * @property string  $name          The unique name of the reference work.
 * @property ?string $external_url  The hyperlink to the source.
 * @property ?Carbon $created_at    The timestamp that indicates when the entity was created.
 * @property ?Carbon $updated_at    The timestamp that indicates when the entity was last updated.
 *
 * @package App\Models
 */
final class ReferenceWork extends Model
{
    /**
     * The attributes that are not mass-assignable.
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

    /**
     * Get the individual article entries associated with this reference work.
     * This defines a One-to-Many relationship where each ArticleReferenceWork serves as a specific entry or record within the larger Reference Work.
     *
     * @return HasMany<ArticleReferenceWork, covariant $this>
     */
    public function articles(): HasMany
    {
        return $this->hasMany(ArticleReferenceWork::class);
    }
}
