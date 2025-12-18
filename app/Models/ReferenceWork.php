<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class ReferenceWork extends Model
{
    /**
     * The attributes that are not mass-assignable.
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

    /**
     * Get the individual article entries associated with this reference work. 
     * This defines a One-to-Many relationship where each ArticleReferenceWork serves as a specific entry or record within the larger Reference Work.
     * 
     * @return HasMany<ArticleReferenceWork, covariant $this>
     */
    public function articles(): HasMany
    {
        return $this->hasMany(ArticleReferenceWork::class);
    }
}
