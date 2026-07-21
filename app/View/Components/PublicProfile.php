<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Builders\ArticleBuilder;
use App\Enums\ArticleStates;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;
use stdClass;

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
            'suggestionCount' => $this->getSuggestionCount(),
            'publicationCount' => $this->getPublicationCount(),
            'kudosCount' => $this->getKudosCount(),
            'viewsCount' => $this->getViewsCount(),
            'conceptCount' => $this->getConceptCount(),
            'totals' => $this->calculateTotals(),
        ]);
    }

    private function getConceptCount(): int
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        return $authenticatedUser->concepts()->count();
    }

    private function getSuggestionCount(): int
    {
        return $this->user->suggestions()->count();
    }

    private function getViewsCount(): int
    {
        return $this->user->suggestions->sum('views');
    }

    private function getKudosCount(): int
    {
        $user = User::withCount(['suggestions as total_upvotes' => function (ArticleBuilder $query) {
            $query->join('votes', 'articles.id', '=', 'votes.votable_id')
                ->where('votes.votable_type', \App\Models\Article::class);
        }])->find($this->user->id);

        return $user->total_upvotes ?? 0;
    }

    private function getPublicationCount(): int
    {
        return $this->user->suggestions()->whereNotNull('published_at')->count();
    }

    private function calculateTotals(): stdClass
    {
        $authorId = $this->user->id ?? auth()->id();

        return collect(ArticleStates::cases())
            ->reduce(function ($query, $status) {
                return $query->selectRaw(
                    expression: 'COUNT(CASE WHEN state = ? THEN 1 END) AS '.mb_strtolower($status->name),
                    bindings: [$status->value]
                );
            }, DB::table('articles')->where('author_id', $authorId)->selectRaw('COUNT(*) AS total'))
            ->first();
    }

    private function contactExists(): bool
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        return $authenticatedUser->contacts->doesntContain($this->user) && $authenticatedUser->isNot($this->user);
    }
}
