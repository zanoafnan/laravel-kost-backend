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

            'min_price' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
            ],

            'max_price' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
                Rule::when(
                    $this->filled('min_price'),
                    ['gte:min_price']
                ),
            ],

            'sort' => [
                'sometimes',
                'nullable',
                Rule::in(['asc', 'desc']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'min_price.numeric' => 'Minimum price must be a number.',
            'min_price.min' => 'Minimum price cannot be negative.',

            'max_price.numeric' => 'Maximum price must be a number.',
            'max_price.min' => 'Maximum price cannot be negative.',
            'max_price.gte' => 'Maximum price must be greater than or equal to minimum price.',

            'sort.in' => 'Sort must be asc or desc.',
        ];
    }
}