<?php

namespace App\Livewire\Etymologies;

use App\Models\Article as Word;
use App\Models\Etymology;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateEtymology extends Component
{
    public Word $word;

    public bool $showForm = false;

    public bool $editing = false;

    public string $origin = '';

    public ?string $sourceLanguage = null;

    public ?string $source = null;

    public const LANGUAGES = [
        'nl'    => 'Nederlands / Vlaams',
        'fr'    => 'Frans',
        'la'    => 'Latijn',
        'en'    => 'Engels',
        'de'    => 'Duits',
        'other' => 'Andere taal',
    ];

    public function mount(Word $word): void
    {
        $this->word = $word;
    }

    protected function rules(): array
    {
        return [
            'origin'         => ['required', 'string', 'min:5', 'max:5000'],
            'sourceLanguage' => ['nullable', Rule::in(array_keys(self::LANGUAGES))],
            'source'         => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'origin'         => 'herkomst',
            'sourceLanguage' => 'brontaal',
            'source'         => 'bron',
        ];
    }

    /**
     * Pas de rolnaam aan naar jouw Spatie-rol.
     */
    protected function canEdit(): bool
    {
        return auth()->check() && auth()->user()->hasRole('redacteur');
    }

    /**
     * Heeft de ingelogde gebruiker al ergens een etymologie ingestuurd?
     */
    protected function userHasSubmitted(): bool
    {
        return auth()->check()
            && Etymology::where('user_id', auth()->id())->where('article_id', $this->word->id)->exists();
    }

    public function open(): void
    {
        abort_unless(auth()->check(), 403);

        if ($this->word->etymology()->exists() || $this->userHasSubmitted()) {
            return;
        }

        $this->showForm = true;
    }

    public function cancel(): void
    {
        $this->reset('origin', 'sourceLanguage', 'source', 'showForm', 'editing');
        $this->resetValidation();
    }

    public function save(): void
    {
        abort_unless(auth()->check(), 403);

        $validated = $this->validate();

        if ($this->word->etymology()->exists()) {
            $this->cancel();
            return;
        }

        if ($this->userHasSubmitted()) {
            $this->cancel();
            session()->flash('etymology-error', 'Je hebt al een etymologie ingestuurd. Je kunt er maar één insturen.');
            return;
        }

        try {
            $this->word->etymology()->create([
                'origin'          => $validated['origin'],
                'source_language' => $validated['sourceLanguage'] ?: null,
                'source'          => $validated['source'] ?: null,
                'user_id'         => auth()->id(),
                // 'status' => 'approved', // enkel nodig als de kolomdefault niet 'approved' is
            ]);
        } catch (UniqueConstraintViolationException) {
            // Iemand was net sneller (zelfde artikel) of de gebruiker heeft al een inzending
            $this->cancel();
            session()->flash('etymology-error', 'Je etymologie kon niet worden opgeslagen. Er bestaat al een inzending.');
            return;
        }

        $this->cancel();

        session()->flash('etymology-saved', 'Bedankt! Je bijdrage is opgeslagen. We voeren nog enkele controles uit alvorens we ze publiceren.');
    }

    public function edit(): void
    {
        abort_unless($this->canEdit(), 403);

        $etymology = $this->word->etymology()->firstOrFail();

        $this->origin         = $etymology->origin;
        $this->sourceLanguage = $etymology->source_language;
        $this->source         = $etymology->source;
        $this->editing        = true;
        $this->showForm       = true;
        $this->resetValidation();
    }

    public function update(): void
    {
        abort_unless($this->canEdit(), 403);

        $validated = $this->validate();

        $etymology = $this->word->etymology()->firstOrFail();

        // user_id blijft ongewijzigd: de oorspronkelijke auteur blijft vermeld.
        $etymology->update([
            'origin'          => $validated['origin'],
            'source_language' => $validated['sourceLanguage'] ?: null,
            'source'          => $validated['source'] ?: null,
        ]);

        $this->cancel();

        session()->flash('etymology-saved', 'De etymologie is bijgewerkt.');
    }

    public function render()
    {
        return view('livewire.etymologies.create-etymology', [
            'languages'    => self::LANGUAGES,
            'etymology'    => $this->word->etymology()->with('user:id,name')->first(),
            'canEdit'      => $this->canEdit(),
            'hasSubmitted' => $this->userHasSubmitted(),
        ]);
    }
}
