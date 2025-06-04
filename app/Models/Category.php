<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Category extends Model
{
    use HasUlids;

    protected $fillable = ['name', 'description'];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Blog::class, 'post_categories');
    }
}
