<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;

class ReportArticleModal extends Component
{
    public Article $article;

    public $melding = '';

    public function mount(Article $article): void
    {
        $this->article = $article;
    }

    public function render()
    {
        return view('livewire.report-article-modal', data: [
            'article' => $this->article
        ]);
    }
}
