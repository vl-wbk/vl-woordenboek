<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Vote extends Model
{
    protected $fillable = ['user_id', 'article_id', 'value'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    protected function casts(): array
    {
        return [
            'value' => 'integer',
        ];
    }

    public function isUpvote(): bool
    {
        return $this->value === 1;
    }

    public function isDownvote(): bool
    {
        return $this->value === -1;
    }
}
