<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Models\AvailabilityRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'credit',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function kosts(): HasMany
    {
        return $this->hasMany(Kost::class, 'owner_id');
    }

    public function availabilityRequests(): HasMany
    {
        return $this->hasMany(AvailabilityRequest::class);
    }
}