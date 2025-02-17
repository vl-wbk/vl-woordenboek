<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Relations\BelongsToManyRegions;
use App\Observers\DefinitionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

final class Definition extends Model
{
    /** @use HasFactory<\Database\Factories\DefinitionFactory> */
    use HasFactory;

    protected $guarded = ['id', 'creator_id', 'editor_id'];

    public function coverageAreas(): MorphToMany
    {
        return $this->morphToMany(Region::class, 'linguistic', 'coverage');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
