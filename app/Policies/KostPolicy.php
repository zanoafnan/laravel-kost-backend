<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Kost;
use App\Models\User;

class KostPolicy
{
    public function view(User $user, Kost $kost): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::OWNER;
    }

    public function update(User $user, Kost $kost): bool
    {
        return $user->role === UserRole::OWNER
            && $kost->owner_id === $user->id;
    }

    public function delete(User $user, Kost $kost): bool
    {
        return $user->role === UserRole::OWNER
            && $kost->owner_id === $user->id;
    }

    public function restore(User $user, Kost $kost): bool
    {
        return $user->role === UserRole::OWNER
            && $kost->owner_id === $user->id;
    }

    public function forceDelete(User $user, Kost $kost): bool
    {
        return false;
    }
}