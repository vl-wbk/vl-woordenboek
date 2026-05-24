<?php

declare(strict_types=1);

namespace App\Actions\Articles;

use App\Attributes\Todo;
use App\Data\EtymologySubmissionData;
use App\Models\Article;
use App\Models\Etymology;
use Illuminate\Support\Facades\DB;
use Throwable;

#[Todo(message: 'Provide docvlocks for the class and methods', author: 'Tjoosten', priority: 'info')]
final readonly class StoreEtymologySubmission
{
    /**
     * @throws Throwable when the database transaction couldn't complete successfully
     */
    #[Todo(message: 'We need to check if the user is getting attached as author from the etymology record', author: 'Tjoosten', priority: 'high')]
    public function execute(Article $article, EtymologySubmissionData $etymologySubmissionData): Etymology
    {
        return DB::transaction(function () use ($article, $etymologySubmissionData): Etymology {
            $submission = $article->etymologies()->create($etymologySubmissionData->toArray());
            flash(text: 'We hebben de informatie goed ontvangen! We gaan er spoedig mee aan de slag.', class: 'alert-success');

            return $submission;
        });
    }
}
