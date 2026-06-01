<?php

declare(strict_types=1);

namespace App\Actions\Articles;

use App\Data\Article\CorrectionData;
use App\Models\Article;
use App\Models\CorrectionProposal;
use Illuminate\Support\Facades\DB;
use Throwable;

final readonly class StoreArticleCorrection
{
    /**
     * @throws Throwable The the storage action couldn't complete successfully.
     */
    public function __invoke(Article $article, CorrectionData $correctionData): void
    {
        DB::transaction(function () use ($article, $correctionData): void {
            /** @var CorrectionProposal $correction */
            $correction = $article->corrections()->make($correctionData->toArray());

            $correction->setCurrentUserAsAuthor();
            $correction->save();
        });
    }
}
