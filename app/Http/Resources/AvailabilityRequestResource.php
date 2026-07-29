<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvailabilityRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],

            'kost' => [
                'id' => $this->kost->id,
                'name' => $this->kost->name,
                'location' => $this->kost->location,
                'price' => $this->kost->price,
            ],

            'credit_used' => $this->credit_used,

            'created_at' => $this->created_at,
        ];
    }
}