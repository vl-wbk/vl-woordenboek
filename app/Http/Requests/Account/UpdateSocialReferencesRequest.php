<?php

namespace App\Http\Requests\Account;

use App\Data\Account\SocialMediaReferenceData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\WithData;

final class UpdateSocialReferencesRequest extends FormRequest
{
	use WithData;
	
	protected string $dataClass = SocialMediaReferenceData::class;
	
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'twitter' => ['max:255', 'nullable', Rule::unique('users', 'twitter')->ignore(Auth::id())],
			'bluesky' => ['max:255', 'nullable', Rule::unique('users','bluesky')->ignore(Auth::id())],
			'website' => ['nullable', 'max:255', 'url'],
        ];
    }
}
