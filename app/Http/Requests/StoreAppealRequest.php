<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Data\AppealData;
use App\Models\Appeal;
use App\Rules\CanAppealReputation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Override;
use Spatie\LaravelData\WithData;

final class StoreAppealRequest extends FormRequest
{
    use WithData;

    protected string $dataClass = AppealData::class;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Appeal::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reputation_log_id' => ['required', 'exists:reputation_logs,id', new CanAppealReputation],
            'reason' => ['required', 'string', 'min:20', 'max:500'],
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'reputation_log_id.required' => 'Kies een reputatiewijziging om aan te vechten.',
            'reason.required'            => 'Geef een reden op.',
            'reason.min'                 => 'Je reden is te kort (min. 20 tekens).',
            'reason.max'                 => 'Je reden is te lang (max. 500 tekens).',
        ];
    }
}
