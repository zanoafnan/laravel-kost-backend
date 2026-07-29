<?php

namespace App\Services;

use App\Models\Kost;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class KostService
{
    public function create(User $owner, array $data): Kost
    {
        return $owner->kosts()->create([
            'name' => $data['name'],
            'description' => $data['description'],
            'location' => $data['location'],
            'price' => $data['price'],
        ]);
    }

    public function ownerKosts(User $owner): LengthAwarePaginator
    {
        return $owner->kosts()
            ->latest()
            ->paginate(10);
    }

    public function update(
        Kost $kost,
        array $data
    ): Kost {
        $kost->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'location' => $data['location'],
            'price' => $data['price'],
        ]);

        return $kost->fresh();
    }

    public function delete(Kost $kost): bool
    {
        return $kost->delete();
    }

    public function search(array $filters): LengthAwarePaginator
    {
        return Kost::query()
            ->when(
                $filters['name'] ?? null,
                function ($query, $name) {
                    $query->where(
                        'name',
                        'like',
                        "%{$name}%"
                    );
                }
            )
            ->when(
                $filters['location'] ?? null,
                function ($query, $location) {
                    $query->where(
                        'location',
                        'like',
                        "%{$location}%"
                    );
                }
            )
            ->when(
                $filters['price'] ?? null,
                function ($query, $price) {
                    $query->where(
                        'price',
                        '<=',
                        $price
                    );
                }
            )
            ->orderBy(
                'price',
                $filters['sort'] ?? 'asc'
            )
            ->paginate(10);
    }

    public function findById(int $id): Kost
    {
        return Kost::findOrFail($id);
    }
}