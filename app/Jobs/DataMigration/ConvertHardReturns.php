<?php

declare(strict_types=1);

namespace App\Jobs\DataMigration;

use App\Models\Article;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class ConvertHardReturns implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Article $article
    ) {}

    public function handle(): void
    {
        $this->article->updateQuietly(attributes: [
            'example' => $this->standarizeLineEndings($this->article->example),
            'description' => $this->standarizeLineEndings($this->article->description),
        ]);
    }

    public function standarizeLineEndings(string $content): string
    {
        return str_replace(["\r\n", "\r"], "  \n", $content);
    }
}
