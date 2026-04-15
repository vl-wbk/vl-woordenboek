<?php

namespace App\Livewire;


use App\Models\Article;
use App\Models\UserExample;
use App\States\ExampleSentence\Approved;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class UserExamplesList extends Component
{
    use WithPagination;

    protected int|string $articleId;

    public string $sortBy = 'created_at';

    public function updatedSortBy(): void
    {
        $this->resetPage();
    }

    public function mount($articleId = null)
{
    // Ensure it has a value, even if it's 0 or a default
    $this->articleId = $articleId;
}

    #[Computed]
    public function article()
    {
        return Article::findOrFail($this->articleId);
    }

    #[Computed]
    public function examples()
    {
        $direction = $this->sortBy === 'created_at_asc' ? 'asc' : 'desc';

        return UserExample::query()
            ->where('article_id', $this->articleId)
            ->whereState('status', Approved::class)
            ->with('author')
            ->orderBy('created_at', $direction)
            ->paginate(4)
            ->setPath(route('word-information.show', ['word' => $this->articleId]));
    }

    public function render(): Renderable
    {
        return view('livewire.user-examples-list', data: [
            'examples' => $this->examples(),
            'word' => $this->article(),
        ]);
    }
}
