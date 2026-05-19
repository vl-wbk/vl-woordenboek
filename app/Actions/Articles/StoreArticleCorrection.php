<?php 

declare(strict_types=1);

namespace App\Actions\Articles;

use App\Data\Article\CorrectionData;
use App\Models\Article;
use Illuminate\Support\Facades\DB;

final readonly class StoreArticleCorrection
{
    public function __invoke(Article $article, CorrectionData $articleCorrectionData): void 
    {
        DB::transaction(function () use ($article, $articleCorrectionData): void {
            $correction = $article->corrections()->make($articleCorrectionData->toArray());
            $correction->setCurrentUserAsAuthor();
            $correction->save();
        });
    }
}