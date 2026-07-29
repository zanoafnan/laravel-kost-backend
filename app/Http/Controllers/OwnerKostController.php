<?php

namespace App\Http\Controllers;

use App\Http\Requests\Kost\StoreKostRequest;
use App\Http\Requests\Kost\UpdateKostRequest;
use App\Http\Resources\KostResource;
use App\Models\Kost;
use App\Services\KostService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OwnerKostController extends Controller
{
    use ApiResponse;

    public function __construct(
        private KostService $kostService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $kosts = $this->kostService->ownerKosts(
            $request->user()
        );

        return $this->success(
            KostResource::collection($kosts),
            'Kost list retrieved successfully'
        );
    }

    public function store(StoreKostRequest $request): JsonResponse
    {
        $kost = $this->kostService->create(
            $request->user(),
            $request->validated()
        );

        return $this->success(
            new KostResource($kost),
            'Kost created successfully',
            201
        );
    }

    public function update(
        UpdateKostRequest $request,
        Kost $kost
    ): JsonResponse {
        $this->authorize(
            'update',
            $kost
        );

        $kost = $this->kostService->update(
            $kost,
            $request->validated()
        );

        return $this->success(
            new KostResource($kost),
            'Kost updated successfully'
        );
    }

    public function destroy(
        Kost $kost
    ): JsonResponse {
        $this->authorize(
            'delete',
            $kost
        );

        $this->kostService->delete($kost);

        return $this->success(
            null,
            'Kost deleted successfully'
        );
    }
}