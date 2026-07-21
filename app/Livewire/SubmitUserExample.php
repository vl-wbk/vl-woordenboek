<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Attributes\Validate;

final class SubmitUserExample extends Component
{
    public Article $article;

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
        $this->article = Article::findOrFail($articleId);
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

        $this->article->userExamples()->create([
            'user_id'          => auth()->id(),
            'contributor_name' => auth()->check() ? auth()->user()->name : ($this->contributorName ?: 'Anoniem'),
            'example'          => $this->example,
            'source'           => $this->source,
        ]);

        $this->submitted = true;
    }

    public function render(): Renderable
    {
        return view('livewire.submit-user-example');
    }
}
