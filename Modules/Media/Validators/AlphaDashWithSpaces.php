<?php

namespace Modules\Media\Validators;

use Illuminate\Contracts\Validation\Rule;

class AlphaDashWithSpaces implements Rule
{
    /**
     * Determine if the validation rule passes.
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return false;
        }

        return preg_match('/^[\pL\pM\pN_\s-]+$/u', $value) > 0;
    }

    /**
     * Get the validation error message.
     * @return string
     */
    public function message()
    {
        return 'The :attribute may only contain letters, numbers, dashes and spaces.';
    }
}
