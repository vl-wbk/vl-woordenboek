<?php

namespace App\Models;

use App\Enums\LanguageStatus;
use App\Models\Relations\BelongsToManyRegions;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Overtrue\LaravelLike\Traits\Likeable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

final class Word extends Model implements AuditableContract
{
    /** @use HasFactory<\Database\Factories\WordFactory> */
    use HasFactory;
    use BelongsToManyRegions;
    use Auditable;
    use Likeable;

    protected $fillable = ['word', 'description', 'author_id', 'status', 'example', 'characteristics'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function definitions(): HasMany
    {
        return $this->hasMany(Definition::class);
    }

    protected function casts(): array
    {
        return [
            'status' => LanguageStatus::class,
        ];
    }
}
