<?php

declare(strict_types=1);

namespace App\Actions\Articles;

use App\Data\EtymologySubmissionData;
use App\Models\Article;
use Illuminate\Support\Facades\DB;

final readonly class StoreEtymologySubmission
{
    public function execute(Article $article, EtymologySubmissionData $etymologySubmissionData): void
    {
        DB::transaction(function () use ($article, $etymologySubmissionData): void {
            $article->etymology()->create($etymologySubmissionData->toArray());
            flash(text: 'We hebben da informatie goed ontvangen! We gaan er spoedig mee aan de slag.', class: 'alert-success');
        });
    }
}
