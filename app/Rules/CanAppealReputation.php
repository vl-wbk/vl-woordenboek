<?php

namespace App\Rules;

use App\Models\ReputationLog;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Translation\PotentiallyTranslatedString;

class CanAppealReputation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $log = ReputationLog::find($value);

        if (! $log || $log->user_id !== Auth::id()) {
            $fail('Deze registratie bestaat niet.');
            return;
        }

        if ($log->type !== 'deduction') {
            $fail('Je kan alleen negatieve aanpassingen aanvechten.');
            return;
        }

        if (Auth::user()->appeals()->where('reputation_log_id', $value)->exists()) {
            $fail('Je hebt dit al aangevochten.');
        }
    }
}
