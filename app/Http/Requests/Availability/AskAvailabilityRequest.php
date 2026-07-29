<?php

namespace App\Http\Requests\Availability;

use Illuminate\Foundation\Http\FormRequest;

class AskAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kost_id' => [
                'required',
                'integer',
                'exists:kosts,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'kost_id.required' => 'Kost is required.',
            'kost_id.integer' => 'Kost ID must be an integer.',
            'kost_id.exists' => 'Selected kost does not exist.',
        ];
    }
}