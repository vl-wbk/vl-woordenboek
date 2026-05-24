<?php

declare(strict_types=1);

namespace App\Actions\Blog;

use Throwable;
use App\Data\Blog\GuestArticleData;
use App\Filament\Clusters\Blog\Resources\Blogs\Enums\Status;
use App\Models\Blog;
use Illuminate\Support\Facades\DB;

/**
 * Service action: StoreGuestArticle
 *
 * This dedicated action, which is declared final and readonly for stability handles the atomic persistence of a new
 * article submitted by a guest. The action guarantees that the article is explicitly tagged with the required
 * 'Status::GuestArticle' and ensures that both the creation of the record and the subsequent assignment of the
 * author are completed successfully or rolled back entirely via a database transaction.
 */
final readonly class StoreGuestArticle
{
	/**
     * Executes the process to store a new guest article in the database.
     *
     * The entire operation is wrapped in a database transaction to guarantee that both the article creation and the
     * author assignment either success of fail together.
     *
     * @param  GuestArticleData $articleData The validated Data Transfer Object (DTO) containing the core article details.
     * @return Blog                          The fully persisted and initialized Blog model instance with its primary key set.
     *
     * @throws Throwable when the database transaction couldn't complete successfully.
     */
    public function handle(GuestArticleData $articleData): Blog
	{
		$attributes = array_merge($articleData->toArray(), ['status' => Status::GuestArticle]);

		return DB::transaction(static function () use ($attributes):Blog {
			$article = Blog::query()->create($attributes);
			$article->setCurrentUserAsAuthor();

			return $article;
		});
	}
}
