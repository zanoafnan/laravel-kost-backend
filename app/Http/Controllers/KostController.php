<?php

namespace App\Http\Controllers;

use App\Http\Requests\Kost\SearchKostRequest;
use App\Http\Resources\KostResource;
use App\Models\Kost;
use App\Services\KostService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class KostController extends Controller
{
    use ApiResponse;

    public function __construct(
        private KostService $kostService
    ) {}

    public function index(SearchKostRequest $request): JsonResponse
    {
        $kosts = $this->kostService->search(
            $request->validated()
        );

        return $this->success(
            KostResource::collection($kosts),
            'Kost list retrieved successfully'
        );
    }

    public function show(Kost $kost): JsonResponse
    {
        return $this->success(
            new KostResource($kost),
            'Kost detail retrieved successfully'
        );
    }
}
