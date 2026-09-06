<?php

namespace App\Models;

use App\Domain\Quality\ArticleReviewDecision;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ArticleReview extends Model
{
    protected $fillable = [
        'article_id',
        'reviewed_by',
        'votes_version',
        'upvotes',
        'downvotes',
        'score',
        'confidence',
        'controversy',
        'review_priority',
        'decision',
        'notes',
    ];

    protected $casts = [
        'votes_version' => 'integer',
        'upvotes' => 'integer',
        'downvotes' => 'integer',
        'score' => 'integer',
        'confidence' => 'integer',
        'controversy' => 'integer',
        'review_priority' => 'integer',
        'decision' => ArticleReviewDecision::class,
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isCurrent(): bool
    {
        return $this->article->votes_version === $this->votes_version;
    }
}
