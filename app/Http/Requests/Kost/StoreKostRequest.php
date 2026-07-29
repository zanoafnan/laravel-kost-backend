<?php

namespace App\Http\Requests\Kost;

use Illuminate\Foundation\Http\FormRequest;

class StoreKostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'price' => [
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