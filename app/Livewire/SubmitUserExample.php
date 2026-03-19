<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserExample;
use Livewire\Attributes\Validate;

class SubmitUserExample extends Component
{
    public int $wordId;

    #[Validate('required|min:10|max:500')]
    public string $example = '';

    #[Validate('nullable|string|max:100')]
    public string $contributorName = '';

    public bool $submitted = false;

    public function mount(int $wordId): void
    {
        $this->wordId = $wordId;
    }

    public function submit(): void
    {
        $this->validate();

        UserExample::create([
            'article_id' => $this->wordId,
            'user_id' => auth()->id(),
            'contributor_name' => auth()->check() ? auth()->user()->name : ($this->contributorName ?: 'Anoniem'),
            'example' => $this->example,
        ]);

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.submit-user-example');
    }
}
