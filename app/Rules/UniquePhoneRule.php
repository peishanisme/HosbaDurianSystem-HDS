<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\User;

class UniquePhoneRule implements ValidationRule
{
    protected ?int $ignoreUserId;

    public function __construct(?int $ignoreUserId = null)
    {
        $this->ignoreUserId = $ignoreUserId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Normalize the input phone (remove leading 0, add 60 if needed)
        $normalizedPhone = ltrim($value, '0');
        if (!str_starts_with($normalizedPhone, '60')) {
            $normalizedPhone = '60' . $normalizedPhone;
        }

        // Check if phone exists in DB
        $query = User::where('phone', $normalizedPhone);

        if ($this->ignoreUserId) {
            $query->where('id', '!=', $this->ignoreUserId);
        }

        if ($query->exists()) {
            $fail('The ' . $attribute . ' has already been taken.');
        }
    }
}
