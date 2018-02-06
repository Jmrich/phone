<?php

namespace App\Validators;

use Illuminate\Validation\Validator;

abstract class BaseValidator
{
    /** @var Validator  */
    protected $validator;

    public function __construct(array $data)
    {
        $this->validator = \Validator::make($data, $this->rules(), $this->messages());
    }

    public function getValidator()
    {
        return $this->validator;
    }

    public function passes()
    {
        $this->addDynamicRules();

        return $this->validator->passes();
    }

    protected function addDynamicRules()
    {
    }

    abstract protected function rules();

    abstract protected function messages();

}
