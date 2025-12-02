<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ArticleReferenceWork extends Pivot
{
    public $incrementing = true;

    /**
     * @var list<string>
     */
    public $with = ['referenceWork'];

    protected $table = 'article_sources';

    /**
     * @return BelongsTo<Article, covariant $this>
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * @return BelongsTo<ReferenceWork, covariant $this>
     */
    public function referenceWork(): BelongsTo
    {
        return $this->belongsTo(ReferenceWork::class);
    }
}
