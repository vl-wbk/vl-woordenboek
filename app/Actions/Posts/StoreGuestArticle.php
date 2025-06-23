<?php

declare(strict_types=1);

namespace App\Actions\Posts;

use App\Data\GuestArticleDataObjectData;
use App\Models\Blog;
use Illuminate\Support\Facades\DB;

/**
 * This action handles the creation of a new guest article.
 * It Ensures that the articles, its author, and its categories are all saved correctly within a single database transaction for data integrity.
 *
 * @package App\Actions\Posts
 */
final readonly class StoreGuestArticle
{
    /**
     * Handle the storage of a new guest article.
     *
     * This method creates a new blog post. It associates the authenticated user as the author.
     * It also synchronizes the article's categories. All these steps occur within a database transaction.
     * This ensures atomicity: if any part fails, the entire operation rolls back.
     *
     * @param GuestArticleDataObjectData $guestArticleDataObject The data transfer object containing all necessary data for the guest article.
     * @return void
     */
    public function handle(GuestArticleDataObjectData $guestArticleDataObject): void
    {
        DB::transaction(function () use ($guestArticleDataObject): void {
            $post = Blog::query()->create($guestArticleDataObject->except('categories')->toArray());

            $post->author()->associate(auth()->user())->save();
            $post->category()->sync($guestArticleDataObject->categories);
        });
    }
}
