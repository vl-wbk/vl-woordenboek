<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Domain\Voting\VoteService;
use App\Models\Article;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Validate;

/**
 * VotingComponent manages the upvote and downvote functionality for dictionary entries.
 *
 * This Livewire component handles real-time interaction for voting on articles in the Vlaams Woordenboek.
 * It integrates with the overtrue/laravel-vote package to persist user preferences.
 *
 * @package App\Livewire
 */
class VotingComponent extends Component
{
    /**
     * The article being voted on.
     */
    public Article $article;

    /**
     * Initializes the component with an article instance.
     *
     * @param Article $article The article to be voted on
     */
    public function mount(Article $article): void
    {
        $this->article = $article;
    }

    public function vote(#[Validate(['required', 'integer', 'in:1,-1'])] int $value, VoteService $voteService): void
    {
        $voteService->vote(
            Auth::user(),
            $this->article,
            $value,
        );

        $this->article->refresh();
    }

    /**
     * Remove the user vote for the article.
     */
    public function removeVote(VoteService $voteService): void
    {
        $voteService->remove(
            Auth::user(),
            $this->article,
        );

        $this->article->refresh();
    }

    /**
     * Renders the component's view.
     *
     * @return Renderable The component's view with voting statistics
     */
    public function render(): Renderable
    {
        return view('livewire.like-words');
    }
}
