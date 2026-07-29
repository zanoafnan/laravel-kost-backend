<?php

namespace App\Http\Requests\Kost;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchKostRequest extends FormRequest
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
                'nullable',
                'string',
                'max:255',
            ],

            'location' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
            ],

            'price' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
            ],

            'sort' => [
                'sometimes',
                'nullable',
                Rule::in([
                    'asc',
                    'desc',
                ]),
            ],
        ];
    }


    public function messages(): array
    {
        return [
            'price.numeric' => 'Price must be a number.',

            'price.min' => 'Price cannot be negative.',

            'sort.in' => 'Sort must be asc or desc.',
        ];
    }
}