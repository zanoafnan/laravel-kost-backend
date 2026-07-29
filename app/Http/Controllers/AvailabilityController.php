<?php

namespace App\Http\Controllers;

use App\Http\Requests\Availability\AskAvailabilityRequest;
use App\Http\Resources\AvailabilityRequestResource;
use App\Models\Kost;
use App\Services\AvailabilityService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class AvailabilityController extends Controller
{
    use ApiResponse;

    public function __construct(
        private AvailabilityService $availabilityService
    ) {
    }

    public function store(
        AskAvailabilityRequest $request
    ): JsonResponse {
        $kost = Kost::findOrFail(
            $request->validated('kost_id')
        );

        $availability = $this->availabilityService->ask(
            $request->user(),
            $kost
        );

        return $this->success(
            new AvailabilityRequestResource($availability),
            'Availability request submitted successfully.',
            201
        );
    }
}