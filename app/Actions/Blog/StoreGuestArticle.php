<?php

declare(strict_types=1);

namespace App\Actions\Blog;

use App\Data\Blog\GuestArticleData;
use App\Filament\Clusters\Blog\Resources\BlogResource\Enums\Status;
use App\Models\Blog;
use Illuminate\Support\Facades\DB;

final readonly class StoreGuestArticle
{
	/**
	 * @throws \Throwable
	 */
	public function handle(GuestArticleData $articleData): Blog
	{
		$attributes = array_merge($articleData->toArray(), ['status' => Status::GuestArticle]);
		
		return DB::transaction(function () use ($attributes):Blog {
			$article = Blog::query()->create($attributes);
			$article->setCurrentUserAsAuthor();
			
			return $article;
		});
	}
}