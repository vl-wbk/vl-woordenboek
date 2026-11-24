<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\SyllableSuggestion;
use App\Services\Syllabification;
use App\Services\SyllableModeration;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class SuggestSyllables extends Component
{
    public string $raw = '';
    public ?int $stress = null;

    #[Locked] public bool $alreadySubmitted = false;
    #[Locked] public bool $done = false;
    #[Locked] public Article $article;

    public bool $autoApproved = false;

    public function mount(Article $article): void
    {
        $this->article = $article;
        $this->alreadySubmitted = SyllableSuggestion::alreadySubmitted($article->id, auth()->id());
    }

    #[Computed]
    public function parts(): array
    {
        return Syllabification::parse($this->raw);
    }

    /** Live foutmelding voor het invoerveld, of null als alles klopt of nog leeg is. */
    #[Computed]
    public function rawError(): ?string
    {
        if (trim($this->raw) === '') {
            return null;
        }

        if (mb_strlen($this->raw) > 80) {
            return 'Dit is te lang. Gebruik maximaal 80 tekens.';
        }

        if (! Syllabification::matches($this->parts, $this->article->word)) {
            return sprintf(
                'Samen vormen deze lettergrepen "%s", maar het woord is "%s".',
                implode('', $this->parts),
                $this->article->word,
            );
        }

        return null;
    }

    #[Computed]
    public function isValidRaw(): bool
    {
        return trim($this->raw) !== '' && $this->rawError === null;
    }

    #[Computed]
    public function canSubmit(): bool
    {
        return $this->isValidRaw && $this->hasValidStress();
    }

    public function updatedRaw(): void
    {
        $this->resetErrorBag();
        // één lettergreep: klemtoon staat dan vanzelf vast
        $this->stress = count($this->parts) === 1 ? 0 : null;
    }

    public function setStress(int $i): void
    {
        $this->resetErrorBag('stress');

        if ($i < 0 || $i >= count($this->parts)) {
            $this->stress = null;
            $this->addError('stress', 'Tik op de lettergreep met de klemtoon.');

            return;
        }

        $this->stress = $i;
    }

    public function save(): void
    {
        abort_unless(auth()->check(), 403);
        $this->resetErrorBag();

        // 1. Al ingediend -> direct stoppen
        if (SyllableSuggestion::alreadySubmitted($this->article->id, auth()->id())) {
            $this->alreadySubmitted = true;

            return;
        }

        // 2. Rate limit -> direct stoppen
        $key = 'syllables:' . auth()->id();
        if (RateLimiter::tooManyAttempts($key, 20)) {
            $this->addError('form', 'Je hebt veel voorstellen ingestuurd. Probeer het over een uur opnieuw.');

            return;
        }

        // 3. Eerste veldfout: lettergrepen -> direct stoppen
        if (trim($this->raw) === '') {
            $this->addError('raw', 'Vul de lettergrepen in, bijvoorbeeld sjot-ten.');

            return;
        }

        if ($this->rawError !== null) {
            $this->addError('raw', $this->rawError);

            return;
        }

        // 4. Tweede veldfout: klemtoon -> direct stoppen
        if (! $this->hasValidStress()) {
            $this->addError('stress', 'Tik op de lettergreep met de klemtoon.');

            return;
        }

        $suggestion = SyllableSuggestion::updateOrCreate(
            ['article_id' => $this->article->id, 'user_id' => auth()->id()],
            [
                'syllables' => $this->parts,
                'stress_index' => $this->stress,
                'signature' => Syllabification::signature($this->parts, $this->stress),
                'status' => SyllableSuggestion::STATUS_PENDING,
            ],
        );

        RateLimiter::hit($key, 3600);

        $this->autoApproved = SyllableModeration::run($this->article, $suggestion);

        if ($this->autoApproved) {
            $this->article->refresh(); // view now shows the approved syllables
        }

        $this->done = true;
    }

    protected function hasValidStress(): bool
    {
        $count = count($this->parts);

        return is_int($this->stress) && $count > 0 && $this->stress >= 0 && $this->stress < $count;
    }

    public function render()
    {
        return view('livewire.suggest-syllables');
    }
}
