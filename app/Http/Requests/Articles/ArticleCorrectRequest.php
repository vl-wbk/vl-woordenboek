<?php

declare(strict_types=1);

namespace App\Http\Requests\Articles;

use App\Data\Article\CorrectionData;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

final class ArticleCorrectRequest extends FormRequest
{
    /** @use WithData<CorrectionData> */
    use WithData;

    protected string $dataClass = CorrectionData::class;

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'beschrijving' => ['required'],
            'beweegredenen' => ['required'],
            'woordsoort' => [],
            'kenmerken' => ['required'],
        ];
    }
}
