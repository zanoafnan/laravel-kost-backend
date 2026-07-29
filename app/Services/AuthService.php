<?php

namespace App\Services;

use App\Enums\CreditAmount;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
    public function register(array $data): array
    {
        $role = UserRole::from($data['role']);

        $credit = match ($role) {
            UserRole::OWNER => CreditAmount::OWNER->value,
            UserRole::REGULAR => CreditAmount::REGULAR->value,
            UserRole::PREMIUM => CreditAmount::PREMIUM->value,
        };

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // otomatis di-hash oleh cast
            'role' => $role,
            'credit' => $credit,
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function login(array $credentials): array
    {
        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid email or password.'],
            ]);
        }

        /** @var User $user */
        $user = Auth::user();

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        /** @var PersonalAccessToken|null $token */
        $token = $user->currentAccessToken();

        $token?->delete();
    }

    public function me(User $user): User
    {
        return $user;
    }
}
