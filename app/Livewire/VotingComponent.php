<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Article;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

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

    /**
     * Register an upvote for the article.
     * * @return mixed
     */
    public function upvote(): void
    {
        if (Auth::guest()) {
            $this->redirect(route('login'));
            return;
        }

        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        $authenticatedUser->upvote($this->article);
        $this->article->refresh();
    }

    /**
     * Register a downvote for the article.
     */
    public function downvote(): void
    {
        if (Auth::guest()) {
            $this->redirect(route('login'));
            return;
        }

        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        $authenticatedUser->downvote($this->article);
        $this->article->refresh();
    }

    /**
     * Renders the component's view.
     *
     * @return Renderable The component's view with voting statistics
     */
    public function render(): Renderable
    {
        return view('livewire.like-words', [
            'upvotesCount' => $this->article->upvoters()->count(),
            'downvotesCount' => $this->article->downvoters()->count(),
            'hasUpvoted' => Auth::check() ? Auth::user()->hasUpvoted($this->article) : false,
            'hasDownvoted' => Auth::check() ? Auth::user()->hasDownvoted($this->article) : false,
        ]);
    }
}
