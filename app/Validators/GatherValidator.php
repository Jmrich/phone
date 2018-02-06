<?php


namespace App\Validators;


use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class GatherValidator extends BaseValidator
{
    protected function rules(): array
    {
        return [
            'gather' => 'required|array|max:2',
            'gather.*' => 'valid_keys:children,options',
            'gather.children' => 'array',
            'gather.options' => 'array',
            'gather.options.*' => 'valid_keys:timeout,language,barge_in',
            'gather.children.*.type' => 'in:say,play',
            'gather.children.*.options' => 'array',
        ];
    }

    protected function addDynamicRules()
    {
        if (isset($this->validator->getData()['gather']['children'])) {
            collect($this->validator->getData()['gather']['children'])
                ->each(function ($child, $key) {
                    switch ($child['type']) {
                        case 'say':
                            $this->addSayRules($key);
                            break;
                        case 'play':
                            $this->addPlayRules($key);
                            break;
                    }
                });
        }
    }

    private function addSayRules($key)
    {
        $this->validator->addRules([
            'gather.children.'.$key.'.noun' => 'required|max:4096',
            'gather.children.'.$key.'.options.*' => 'valid_keys:voice,loop,language',
            'gather.children.'.$key.'.options.voice' => 'in:man,woman,alice',
            'gather.children.'.$key.'.options.loop' => 'int|min:0',
            'gather.children.'.$key.'.options.language' => 'string',
        ]);
    }

    private function addPlayRules($key)
    {
        $this->validator->addRules([
            'gather.children.'.$key.'.noun' => 'required',
            'gather.children.'.$key.'.options.*' => 'valid_keys:loop',
            'gather.children.'.$key.'.options.loop' => 'int|min:0',
        ]);
    }

    protected function messages()
    {
        return [
            'gather.0.in' => 'Only say or play can be nested within a gather.'
        ];
    }
}
