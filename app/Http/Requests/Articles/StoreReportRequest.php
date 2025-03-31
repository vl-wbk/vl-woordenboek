<?php

declare(strict_types=1);

namespace App\Http\Requests\Articles;

use App\Data\ArticleReportData;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

final class StoreReportRequest extends FormRequest
{
    use WithData;

    protected string $dataClass = ArticleReportData::class;

    public function rules(): array
    {
        return ['melding' => ['required']];
    }
}
