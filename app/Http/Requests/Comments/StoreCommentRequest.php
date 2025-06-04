<?php

declare(strict_types=1);

namespace App\Http\Requests\Comments;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

final class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('canComment', $this->blog);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reactie' => ['required'],
        ];
    }
}
