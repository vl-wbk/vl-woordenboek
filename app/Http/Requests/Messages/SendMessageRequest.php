<?php

namespace App\Http\Requests\Messages;

use App\Data\MessageObjectData;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

final class SendMessageRequest extends FormRequest
{
	use WithData;
	
	protected string $dataClass = MessageObjectData::class;
	
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'ontvanger' => ['required', 'exists:App\Models\User,name'],
			'onderwerp' => ['required', 'max:255'],
			'bericht' => ['required'],
        ];
    }
}
