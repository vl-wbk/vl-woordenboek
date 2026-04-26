<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[Unguarded]
#[Table(name: 'article_sources', incrementing: true)]
final class ArticleReferenceWork extends Pivot
{
    /**
     * @var list<string>
     */
    public $with = ['referenceWork'];

    /**
     * Returns the article this source entry belongs to. 
     * Inverse of Article::sources(). Use this when you have a pivot row and need to access the Article it was cited in. 
     * 
     * @return BelongsTo<Article, covariant $this>
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * 
     * @return BelongsTo<ReferenceWork, covariant $this>
     */
    public function referenceWork(): BelongsTo
    {
        return $this->belongsTo(ReferenceWork::class);
    }
}
 