<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\SyllableSuggestion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Locked;
use Livewire\Component;

class DeleteDecidedSyllables extends Component
{
    #[Locked]
    public Article $word;

    public bool $showModal = false;
    public bool $isMobile = false;

    public function mount(Article $word): void
    {
        $this->word = $word;
    }

    public function openModal(): void
    {
        abort_unless($this->canDelete(), 403);

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function delete(bool $fromMobile = false): void
    {
        if ($fromMobile || $this->isMobile) {
            return;
        }

        abort_unless($this->canDelete(), 403);

        DB::transaction(function (): void {
            SyllableSuggestion::query()
                ->where('article_id', $this->word->id)
                ->where('status', '!=', SyllableSuggestion::STATUS_PENDING)
                ->delete();

            $this->word->update([
                'syllables' => null,
                'stress_index' => null,
            ]);
        });

        $this->showModal = false;

        $this->redirect(request()->header('Referer', url()->current()));
    }

    protected function canDelete(): bool
    {
        return Gate::allows('update', $this->word);
    }

    public function render()
    {
        return view('livewire.delete-decided-syllables');
    }
}
