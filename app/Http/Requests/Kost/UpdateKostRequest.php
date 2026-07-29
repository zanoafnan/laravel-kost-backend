<?php

namespace App\Http\Requests\Kost;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'sometimes',
                'required',
                'string',
            ],

            'location' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'price' => [
                'sometimes',
                'required',
                'numeric',
                'min:0',
            ],
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'Kost name is required.',
            'description.required' => 'Kost description is required.',
            'location.required' => 'Kost location is required.',
            'price.required' => 'Kost price is required.',
            'price.numeric' => 'Kost price must be a number.',
        ];
    }
}