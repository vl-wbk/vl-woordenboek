<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LanguageStatus;
use App\Models\Relations\BelongsToEditor;
use App\Models\Relations\BelongsToManyRegions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Overtrue\LaravelLike\Traits\Likeable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

final class Word extends Model implements AuditableContract
{
    /** @use HasFactory<\Database\Factories\WordFactory> */
    use HasFactory;
    use BelongsToManyRegions;
    use BelongsToEditor;
    use Auditable;
    use Likeable;

    protected $fillable = ['word', 'description', 'author_id', 'status', 'example', 'characteristics'];

    /**
     * Attributes to exclude from the Auditing system.
     *
     * @var array<int, string>
     */
    protected $auditExclude = ['editor_id'];

    /**
     * @return BelongsTo<User, covariant $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * @return HasMany<Definition, covariant $this>
     */
    public function definitions(): HasMany
    {
        return $this->hasMany(Definition::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => LanguageStatus::class,
        ];
    }
}
