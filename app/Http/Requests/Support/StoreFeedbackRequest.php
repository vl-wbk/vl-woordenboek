<?php

declare(strict_types=1);

namespace App\Http\Requests\Support;

use Illuminate\Contracts\Validation\ValidationRule;
use App\Data\FeedbackSubmissionData;
use App\Enums\FeedbackTrueFalse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Spatie\LaravelData\WithData;

final class StoreFeedbackRequest extends FormRequest
{
    /**
     * @use WithData<FeedbackSubmissionData>
     */
    use WithData;

    protected string $dataClass = FeedbackSubmissionData::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'naam' => ['required', 'string', 'max:255'],
            'eerste_bezoek' => ['required', new Enum(FeedbackTrueFalse::class)],
            'resultaten_gevonden' => ['required', new Enum(FeedbackTrueFalse::class)],
        ];
    }
}
