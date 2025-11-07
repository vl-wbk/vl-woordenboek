<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class ReferenceWork extends Model
{
    protected $guarded = ['id'];

    public function articles(): HasMany
    {
        return $this->hasMany(ArticleReferenceWork::class);
    }
}
