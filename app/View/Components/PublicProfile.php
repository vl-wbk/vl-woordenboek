<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

/**
 * @todo After initial deployment refactor this code
 */
final class PublicProfile extends Component
{
    /**
     * @var array{0: int, 1: int}
     */
    protected array $cacheTTL = [10, 60];

    public function __construct(
        public User $user,
    ) {}

    public function render(): View
    {
        return view('components.public-profile', data: [
            'user' => $this->user,
            'contactExist' => $this->contactExists(),
            'suggestedArticleCount' => $this->getSuggestedArticleCount(),
            'suggestedEtymologiesCount' => $this->getSuggestedEtymologyCount(),
            'reportCount' => $this->getArticleReportCount(),
            'articleCount' => $this->getArticleCount(),
        ]);
    }

    /**
     * @return Collection<string, string>
     */
    public function getArticleReportCount(): Collection
    {
        return Cache::flexible('articles_' . $this->user->id, $this->cacheTTL, function (): Collection {
            return collect(['total' => toHumanReadableNumber($this->user->reports()->count())]);
        });
    }

    private function contactExists(): bool
    {
        return auth()->user()->contacts->doesntContain($this->user) && auth()->user()->isNot($this->user);
    }

    /**
     * @return Collection<string, string>
     */
    private function getSuggestedArticleCount(): Collection
    {
        return $this->getCountForRelation('suggestions', 'suggested_articles');
    }

    /**
     * @return Collection<string, string>
     */
    private function getArticleCount(): Collection
    {
        return $this->getCountForRelation('articles', 'articles_');
    }

    /**
     * @return Collection<string, string>
     */
    private function getSuggestedEtymologyCount(): Collection
    {
        return $this->getCountForRelation('etymologies', 'suggested_etymologies');
    }

    /**
     * @return Collection<string, string>
     */
    private function getCountForRelation(string $relation, string $cachePrefix): Collection
    {
        $cacheKey = "{$cachePrefix}_{$this->user->id}";

        return Cache::flexible($cacheKey, $this->cacheTTL, function () use ($relation): Collection {
            return collect(['total' => toHumanReadableNumber($this->user->{$relation}()->published()->count())]);
        });
    }
}
