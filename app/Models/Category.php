<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\CategoryBuilder;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * Class Category
 *
 * The Category model represents distinct categories for organizing content within the application blog posts.
 * This model is typically used to classify articles, blog posts, or other content types, allowing for better organization, navigation, and filtering of information.
 *
 * It uses ULID (Universally Unique Lexicographically Sortable Identifier) for its primary key,ensuring unique and sortable identifiers across distributed systems.
 *
 * @property string      $id           The ULID primary key of the category.
 * @property string      $name         The name of the category (e.g., 'Technology', 'Travel', 'Food').
 * @property ?string     $description  A brief description of the category, nullable.
 * @property Carbon|null $created_at   Timestamp when the category was created.
 * @property Carbon|null $updated_at   Timestamp when the category was last updated.
 *
 * @package App\Models
 */
final class Category extends Model
{
    use HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * These fields can be safely set via mass assignment (e.g., using `Category::create()` or `Category->fill()`).
     * The 'name', 'internal' and 'description' attributes are allowed to be filled directly.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'internal', 'description'];

    /**
     * Get the posts that belong to this category.
     *
     * This defines a Many-to-Many relationship between `Category` and `Blog` models.
     * It indicates that a category can have many blog posts, and a blog post can belong
     * to many categories. The relationship is managed through an intermediate pivot table,
     * named 'post_categories' by default, which typically stores `category_id` and `blog_id`.
     *
     * @return BelongsToMany<Blog>
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Blog::class, 'post_categories');
    }

    public function newEloquentBuilder($query): Builder
    {
        return new CategoryBuilder($query);
    }
}
