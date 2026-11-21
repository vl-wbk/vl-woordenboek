<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Article;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use LogicException;

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
     * Controls the visibility of the downvote feedback input form.
     */
    public bool $showFeedbackForm = false;

    /**
     * The reason provided by the user when downvoting.
     */
    public string $downvoteReason = '';

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
     */
    public function upvote(): void
    {
        if (Auth::guest()) {
            $this->redirect(route('login'));
            return;
        }

        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        // Hide feedback form if switching to upvote
        $this->showFeedbackForm = false;
        $this->downvoteReason = '';

        $authenticatedUser->upvote($this->article);
        $this->article->refresh();
    }

    /**
     * Register a downvote for the article and toggle the feedback form.
     */
    public function downvote(): void
    {
        if (Auth::guest()) {
            $this->redirect(route('login'));
            return;
        }

        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        // Controleer of de gebruiker al had gedownvoted VOORDAT we de actie uitvoeren
        $alreadyDownvoted = $authenticatedUser->hasDownvoted($this->article);

        // Voer de downvote uit (overtrue/laravel-vote toggelt dit vaak of zet het aan)
        $authenticatedUser->downvote($this->article);
        $this->article->refresh();

        // Controleer de nieuwe status na de actie
        $isNowDownvoted = $authenticatedUser->hasDownvoted($this->article);

        // Toon het formulier ALLEEN als de gebruiker net 'Nee' heeft aangezet (en het nog niet had)
        if ($isNowDownvoted && !$alreadyDownvoted) {
            $this->showFeedbackForm = true;
        } else {
            // Als ze al hadden gedownvoted (of de stem is ingetrokken), verberg het formulier
            $this->showFeedbackForm = false;
            $this->downvoteReason = '';
        }
    }

    /**
     * Submit feedback along with the downvote.
     */
    public function submitFeedback(): void
    {
        if (Auth::guest()) {
            $this->redirect(route('login'));
            return;
        }

        // Throws an AuthorizationException (403) when the policy denies.
        Gate::authorize('submitFeedback', $this->article);

        $this->validate(
            rules: ['downvoteReason' => ['required', 'string', 'max:1000']],
            messages: [
                'downvoteReason.required' => 'Laat even weten wat er beter kon, zodat we het artikel kunnen verbeteren.',
                'downvoteReason.string'   => 'Je toelichting moet uit gewone tekst bestaan.',
                'downvoteReason.max'      => 'Je toelichting is te lang. Hou het onder :max tekens (je zit er nu boven).',
            ],
        );

        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        // The policy already guarantees an existing downvote, so the extra hasDownvoted() check is gone.
        $authenticatedUser->votes()
            ->where('votable_type', $this->article::class)
            ->where('votable_id', $this->article->id)
            ->update(['feedback' => $this->downvoteReason]);

        $this->showFeedbackForm = false;
        $this->downvoteReason = '';

        session()->flash('message', 'Bedankt voor je feedback!');
    }

    /**
     * Geeft de reden terug waarom de steunknop verborgen is, of null als hij getoond mag worden.
     *
     * @return 'disabled'|'missing_link'|null
     */
    private function supportButtonIssue(): ?string
    {
        if (! Config::boolean('flemish-dictionary.buy-me-a-coffee.enabled', false)) {
            return 'disabled';
        }

        if ($this->fetchSupportLink() === null) {
            Log::warning(
                'Buy Me a Coffee-knop verborgen: de knop staat aan, maar er is geen link ingesteld. '
                . 'Zet BUY_ME_A_COFFEE_URL in .env (config: flemish-dictionary.buy-me-a-coffee.hyperlink) '
                . 'of zet BUY_ME_A_COFFEE_ENABLED=false.'
            );

            return 'missing_link';
        }

        return null;
    }

    private function fetchSupportLink(): ?string
    {
        $link = Config::get('flemish-dictionary.buy-me-a-coffee.hyperlink');

        return is_string($link) && $link !== '' ? $link : null;
    }

    /**
     * Renders the component's view.
     *
     * @return Renderable The component's view with voting statistics
     */
    public function render(): Renderable
    {
        $supportButtonIssue = $this->supportButtonIssue();

        return view('livewire.like-words', [
            'buyMeACoffeeEnabled' => $supportButtonIssue === null,
            'supportLink' => $this->fetchSupportLink(),
            'supportButtonIssue' => $supportButtonIssue,
            'canGiveFeedback' => Auth::check() && Auth::user()->can('submitFeedback', $this->article),
            'upvotesCount' => $this->article->upvoters()->count(),
            'downvotesCount' => $this->article->downvoters()->count(),
            'hasUpvoted' => Auth::check() ? Auth::user()->hasUpvoted($this->article) : false,
            'hasDownvoted' => Auth::check() ? Auth::user()->hasDownvoted($this->article) : false,
        ]);
    }
}
