<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use JetBrains\PhpStorm\Deprecated;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

#[Deprecated('Data structure wont be used. So we need to remove the definition related code.')]
final class Definition extends Model implements AuditableContract
{
    /** @use HasFactory<\Database\Factories\DefinitionFactory> */
    use HasFactory;
    use Auditable;

    protected $fillable = ['creator_id', 'editor_id', 'description', 'example'];

    public function coverageAreas(): MorphToMany
    {
        return $this->morphToMany(Region::class, 'linguistic', 'coverage');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
