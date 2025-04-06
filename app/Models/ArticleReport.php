<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Relations\BelongsToAuthor;
use Illuminate\Database\Eloquent\Model;

final class ArticleReport extends Model
{
    use BelongsToAuthor;

    protected $guarded = ['id'];
}
