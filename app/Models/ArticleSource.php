<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleSource extends Model
{
    /**
     * @var list<string>
     */
    protected $guarded = ['id'];

    /**
     * @var list<string>
     */
    protected $with = ['reference'];

    /**
     * @return BelongsTo<ReferenceWork, covariant $this>
     */
    public function reference(): BelongsTo
    {
        return $this->belongsTo(ReferenceWork::class, 'reference_work_id');
    }
}
