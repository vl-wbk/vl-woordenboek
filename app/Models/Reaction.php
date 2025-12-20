<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Articles\InsightCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int                         $id 
 * @property int|null                    $article_id
 * @property InsightCategory             $insight_category
 * @property ?string                     $title
 * @property string                      $body 
 * @property string                      $author
 * @property \Illuminate\Support\Carbon  $created_at 
 * @property \Illuminate\Support\Carbon  $updated_at
 */
final class Reaction extends Model
{
    /** @use HasFactory<\Database\Factories\ReactionFactory> */
    use HasFactory;

    /**
     * The attributes that are mass-assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['insight_category'];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, \BackedEnum>
     */
    protected $attributes = [
        'insight_category' => InsightCategory::Uncategorized,
    ];

    /**
     * Get the attrbiutes that should be cast. 
     * This ensures that the insight_category is automatically converted to/from the InsightCategory Enum instance.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'insight_category' => InsightCategory::class,
        ];
    }
}
