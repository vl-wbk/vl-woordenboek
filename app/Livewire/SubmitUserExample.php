<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserExample;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;

class SubmitUserExample extends Component
{
    public int|string|null $articleId;

    public ?string $cssClasses = null;

    #[Validate('required|min:10|max:500')]
    public string $example = '';

    #[Validate('required|string|max:255')]
    public string $source = '';

    #[Validate('nullable|string|max:100')]
    public string $contributorName = '';

    public bool $submitted = false;

    public function mount($articleId = null, ?string $cssClasses = null): void
    {
        $this->articleId = $articleId;
        $this->cssClasses = $cssClasses;
    }

    /**
     * Validates and persists the user example to the database.
     *
     * This method triggers the standard Livewire validation. Upon success, it creates a 
     * UserExample record, automatically attributing it to the logged-in user or 
     * marking it as anonymous/guest-named.
     *
     * @return void
     */
    public function submit(): void
    {
        $this->validate();

        UserExample::create([
            'article_id' => $this->articleId,
            'user_id' => auth()->id(),
            'contributor_name' => auth()->check() ? auth()->user()->name : ($this->contributorName ?: 'Anoniem'),
            'example' => $this->example,
            'source' => $this->source,
            'cssClasses' => $this->cssClasses,
        ]);

        $this->submitted = true;
    }

    public function render(): Renderable
    {
        return view('livewire.submit-user-example');
    }
}
