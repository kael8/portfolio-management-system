<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AtLeastOneField implements Rule
{
    protected $fields;

    public function __construct($fields)
    {
        $this->fields = $fields;
    }

    public function passes($attribute, $value)
    {
        foreach ($this->fields as $field) {
            if (!empty($value[$field])) {
                return true;
            }
        }
        return false;
    }

    public function message()
    {
        return 'At least one of the :attribute fields must be provided.';
    }
}