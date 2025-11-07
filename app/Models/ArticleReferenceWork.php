<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ArticleReferenceWork extends Pivot
{
    public $incrementing = true;

    public $with = ['referenceWork'];

    protected $table = 'article_sources';

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function referenceWork(): BelongsTo
    {
        return $this->belongsTo(ReferenceWork::class);
    }
}
