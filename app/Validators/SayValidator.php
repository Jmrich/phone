<?php

namespace App\Validators;

use Illuminate\Validation\Rule;

class SayValidator extends BaseValidator
{
    protected function passes()
    {
        // TODO: Implement passes() method.
    }

    protected function rules()
    {
        return [
            'noun' => 'required|string|max:4096',
            'options' => 'array',
            'options.*' => [
                Rule::in('voice', 'loop', 'language')
            ],
            'options.voice' => [
                Rule::in('man', 'woman', 'alice')
            ],
            'options.loop' => 'int|min:0',
            'options.language' => 'string'
        ];
    }

    protected function messages()
    {
        return [];
    }
}
