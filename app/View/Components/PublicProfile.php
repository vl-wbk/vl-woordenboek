<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Builders\ArticleBuilder;
use App\Enums\ArticleStates;
use App\Models\Article;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\Builder;
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
        // In your Controller
$contributionData = Article::where('author_id', $this->user->id)
    ->where('created_at', '>=', now()->subYear())
        ->get()
        ->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        })
        ->map(function ($dayActivities) {
            return $dayActivities->count();
        })
        ->toArray();

        return view('components.public-profile', data: [
            'user' => $this->user,
            'contactExist' => $this->contactExists(),
            'totals' => $this->calculateTotals(),
            'contributionData' => $contributionData
        ]);
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
        return auth()->user()->contacts->doesntContain($this->user) && auth()->user()->isNot($this->user);
    }
}
