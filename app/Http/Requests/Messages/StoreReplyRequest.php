<?php

declare(strict_types=1);

namespace App\Http\Requests\Messages;

use Illuminate\Contracts\Validation\ValidationRule;
use App\Data\Message\ReplyDataObject;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

final class StoreReplyRequest extends FormRequest
{
	use WithData;
	
	protected string $dataClass = ReplyDataObject::class;
	
    public function authorize(): bool
    {
        return $this->user()->can('reply', $this->thread);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ['bericht' => ['required']];
    }
}
