<?php

declare(strict_types=1);

namespace App\Http\Requests\Articles;

use App\Data\EtymologySubmissionData;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

final class StoreEtymologyRequest extends FormRequest
{
    use WithData;

    protected string $dataClass = EtymologySubmissionData::class;

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bron' => ['required', 'max:255'],
            'type' => ['required'],
            'oorspronkelijke_taal' => ['required', 'max:255'],
            'oorspronkelijke_vorm' => ['required', 'max:255'],
        ];
    }
}
