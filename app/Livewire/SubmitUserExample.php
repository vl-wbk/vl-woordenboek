<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserExample;
use Livewire\Attributes\Validate;

class SubmitUserExample extends Component
{
    public int $wordId;

    public ?string $cssClasses = null;

    #[Validate('required|min:10|max:500')]
    public string $example = '';

    #[Validate('required|string|max:255')]
    public string $source = '';

    #[Validate('nullable|string|max:100')]
    public string $contributorName = '';

    public bool $submitted = false;

    public function mount(int $wordId, ?string $cssClasses = null): void
    {
        $this->wordId = $wordId;
        $this->cssClasses = $cssClasses;
    }

    public function submit(): void
    {
        $this->validate();

        UserExample::create([
            'article_id' => $this->wordId,
            'user_id' => auth()->id(),
            'contributor_name' => auth()->check() ? auth()->user()->name : ($this->contributorName ?: 'Anoniem'),
            'example' => $this->example,
            'source' => $this->source,
            'cssClasses' => $this->cssClasses,
        ]);

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.submit-user-example');
    }
}
