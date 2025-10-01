<?php

declare(strict_types=1);

namespace App\Http\Requests\Articles;

use Illuminate\Contracts\Validation\ValidationRule;
use App\Data\EtymologySubmissionData;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

final class StoreEtymologyRequest extends FormRequest
{
    /** @use WithData<EtymologySubmissionData> */
    use WithData;

    protected string $dataClass = EtymologySubmissionData::class;

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bron_naam' => ['required', 'max:255'],
            'etymologie' => ['required'],
        ];
    }
}
