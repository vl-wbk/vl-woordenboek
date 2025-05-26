<?php

declare(strict_types=1);

namespace App\Http\Requests\Support;

use App\Enums\FeedbackTrueFalse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

final class StoreFeedbackRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
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
