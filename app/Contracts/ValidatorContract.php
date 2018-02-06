<?php

namespace App\Contracts;

interface ValidatorContract
{
    public function rules();

    public function getValidator();

    public function passes();

    public function messages();
}
