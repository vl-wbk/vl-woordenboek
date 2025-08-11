<?php

declare(strict_types=1);

namespace App\Actions\Articles;

use App\Data\EtymologySubmissionData;
use App\Models\Article;
use App\Models\Etymology;
use Illuminate\Support\Facades\DB;

final readonly class StoreEtymologySubmission
{
    /**
     * @todo We need to check if the user id is getting attached as author from the etymology record.
     */
    public function execute(Article $article, EtymologySubmissionData $etymologySubmissionData): Etymology
    {
        return DB::transaction(function () use ($article, $etymologySubmissionData): Etymology {
            $submission = $article->etymologies()->create($etymologySubmissionData->toArray());
            flash(text: 'We hebben de informatie goed ontvangen! We gaan er spoedig mee aan de slag.', class: 'alert-success');

            return $submission;
        });
    }
}
