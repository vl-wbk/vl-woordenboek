<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class Region extends Model
{
    /** @use HasFactory<\Database\Factories\RegionFactory> */
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * @return MorphTo<\Illuminate\Database\Eloquent\Model, covariant $this>
     */
    public function linguistic(): MorphTo
    {
        return $this->morphTo();
    }
}
