<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

/**
 * LikeWords manages the like functionality for dictionary entries.
 *
 * This Livewire component handles real-time interaction for liking and unliking articles in the Vlaams Woordenboek.
 * It provides seamless user interaction without page refreshes and maintains an accurate count of article likes.
 */
final class LikeWords extends Component
{
    /**
     * The article being liked or unliked.
     *
     * This public property is automatically bound to the component's state and persisted between requests.
     * It represents the current dictionary entry being interacted with.
     */
    public Article $article;

    /**
     * Initializes the component with an article instance.
     *
     * This lifecycle hook sets up the component with the article that will be the target of like/unlike actions.
     * It runs when the component is first instantiated on the page.
     *
     * @param  Article  $article  The article to be liked/unliked
     */
    public function mount(Article $article): void
    {
        $this->article = $article;
    }

    /**
     * Handles the like action for the current article.
     *
     * This method is triggered when a user clicks the like button.
     * It uses Laravel's authentication system to identify the current user and records their like for the article.
     */
    public function likeArticle(): void
    {
        auth()->user()->like($this->article);
    }

    /**
     * Handles the unlike action for the current article.
     *
     * This method is triggered when a user clicks to remove their like.
     * It uses Laravel's authentication system to identify the current user and removes their like from the article.
     */
    public function dislikeArticle(): void
    {
        auth()->user()->unlike($this->article);
    }

    /**
     * Renders the component's view.
     *
     * This method prepares the data needed for the component's template, including the current article and its total number of likes.
     * The view updates automatically when like/unlike actions occur.
     *
     * @return Renderable The component's view with required data
     */
    public function render(): Renderable
    {
        return view('livewire.like-words', [
            'article' => $this->article,
            'upvotes' => $this->article->likers()->count(),
        ]);
    }
}
