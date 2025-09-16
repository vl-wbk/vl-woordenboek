<?php

declare(strict_types=1);
	
namespace App\View\Components;

use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

/**
 * @todo After initial deployment refactor this code
 */
final class PublicProfile extends Component
{
	protected array $cacheTTL = [10, 60];
	
	public function __construct(
		public User $user,
	) {
	}
	
	public function render(): View|Closure|string
	{
		return view('components.public-profile', data: [
			'user' => $this->user,
			'suggestedArticleCount' => $this->getSuggestedArticleCount(),
			'suggestedEtymologiesCount' => $this->getSuggestedEtymologyCount(),
			'reportCount' => $this->getArticleReportCount(),
			'articleCount' => $this->getArticleCount(),
		]);
	}
	
	public function getArticleReportCount(): Collection
	{
		return $this->getCountForRelation('reports', 'article_reports');
	}
	
	private function getSuggestedArticleCount(): Collection
	{
		return $this->getCountForRelation('suggestions', 'suggested_articles');
	}
	
	private function getArticleCount(): Collection
	{
		return $this->getCountForRelation('articles', 'articles_');
	}
	
	private function getSuggestedEtymologyCount(): Collection
	{
		return $this->getCountForRelation('etymologies', 'suggested_etymologies');
	}
	
	private function getCountForRelation(string $relation, string $cachePrefix): Collection
	{
		$cacheKey = "{$cachePrefix}_{$this->user->id}";
		return Cache::flexible($cacheKey, $this->cacheTTL, function () use ($relation): Collection {
			return collect([
				'total' => $this->user->{$relation}()->count(),
				'week' => $this->user->{$relation}()
					->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
					->count(),
			]);
		});
	}
}
