<?php

declare(strict_types=1);

namespace App\Http\Requests\Articles;

use App\Data\EtymologySubmissionData;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

final class StoreEtymologyRequest extends FormRequest
{
    /** @use WithData<EtymologySubmissionData> */
    use WithData;

    protected string $dataClass = EtymologySubmissionData::class;

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bron_naam' => ['required', 'max:255'],
            'etymologie' => ['required'],
        ];
    }
}
