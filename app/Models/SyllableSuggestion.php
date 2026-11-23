<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyllableSuggestion extends Model
{
    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    // $fillable i.p.v. $guarded: status, user_id enz. kunnen nooit per ongeluk
    // via mass assignment worden gezet vanuit request-data.
    protected $fillable = [
        'article_id',
        'user_id',
        'syllables',
        'stress_index',
        'signature',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'syllables'    => 'array',
            'stress_index' => 'integer',
        ];
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForArticleAndUser(Builder $query, int $articleId, int $userId): Builder
    {
        return $query->where('article_id', $articleId)->where('user_id', $userId);
    }

    /**
     * Eén plek voor de "heeft deze gebruiker al ingestuurd?"-check,
     * zodat mount() en save() niet dezelfde query dupliceren.
     */
    public static function alreadySubmitted(int $articleId, ?int $userId): bool
    {
        return $userId !== null
            && static::forArticleAndUser($articleId, $userId)->exists();
    }
}
