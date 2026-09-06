<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ArticleQuality extends Model
{
    protected $table = 'article_quality';

    protected $fillable = ['article_id', 'upvotes', 'downvotes', 'score', 'confidence', 'controversy', 'review_priority', 'calculated_at'];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    protected function casts(): array
    {
        return [
            'upvotes' => 'integer',
            'downvotes' => 'integer',
            'score' => 'integer',
            'confidence' => 'integer',
            'controversy' => 'integer',
            'review_priority' => 'integer',
            'calculated_at' => 'datetime',
        ];
    }
}
