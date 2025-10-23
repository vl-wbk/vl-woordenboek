<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleSource extends Model
{
    protected $guarded = ['id'];

    protected $with = ['reference'];

    public function reference(): BelongsTo
    {
        return $this->belongsTo(ReferenceWork::class, 'reference_work_id');
    }
}
