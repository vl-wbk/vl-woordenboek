<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\SourceCitation;
use App\Enums\Articles\ReferenceWorkType;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected function casts(): array
    {
        return [
            'source_citation' => SourceCitation::class,
        ];
    }
}
