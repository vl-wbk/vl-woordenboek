<?php

declare(strict_types=1);

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

final class StoreContactRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'gebruikersnaam' => ['required', 'exists:App\Models\User,name'],
        ];
    }
}
