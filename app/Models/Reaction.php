<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Articles\InsightCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Reaction extends Model
{
    use HasFactory;

    protected $fillable = ['insight_category'];

    protected $attributes = [
        'insight_category' => InsightCategory::Uncategorized,
    ];

    protected function casts(): array
    {
        return [
            'insight_category' => InsightCategory::class,
        ];
    }
}
