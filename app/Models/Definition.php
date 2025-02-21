<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

final class Definition extends Model implements AuditableContract
{
    /** @use HasFactory<\Database\Factories\DefinitionFactory> */
    use HasFactory;
    use Auditable;

    /**
     * @return list<string>
     */
    protected $fillable = ['creator_id', 'editor_id', 'description', 'example'];

    /**
     * @return MorphToMany<Region, covariant $this>
     */
    public function coverageAreas(): MorphToMany
    {
        return $this->morphToMany(Region::class, 'linguistic', 'coverage');
    }

    /**
     * @return BelongsTo<User, covariant $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
