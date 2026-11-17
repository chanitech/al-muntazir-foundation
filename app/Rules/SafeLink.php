<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Accepts web addresses (https://...), e-mail / phone links and links to
 * pages of this site such as "/contact" or "#programs".
 */
class SafeLink implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = trim((string) $value);

        if ($value === '') {
            return;
        }

        if (preg_match('#^/(?!/)#', $value) || str_starts_with($value, '#')) {
            return;
        }

        if (preg_match('#^(mailto|tel):#i', $value)) {
            return;
        }

        if (filter_var($value, FILTER_VALIDATE_URL) && preg_match('#^https?://#i', $value)) {
            return;
        }

        $fail('The :attribute must be a full web address (https://...) or a page on this site starting with "/".');
    }
}
