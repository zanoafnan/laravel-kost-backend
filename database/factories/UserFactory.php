<?php

namespace Database\Factories;

use App\Enums\CreditAmount;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),

            'email' => fake()->unique()->safeEmail(),

            'password' => static::$password ??= Hash::make('password123'),

            'role' => UserRole::REGULAR,

            'credit' => CreditAmount::REGULAR->value,
        ];
    }

    public function owner(): static
    {
        return $this->state(fn() => [
            'role' => UserRole::OWNER,
            'credit' => CreditAmount::OWNER->value,
        ]);
    }

    public function regular(): static
    {
        return $this->state(fn() => [
            'role' => UserRole::REGULAR,
            'credit' => CreditAmount::REGULAR->value,
        ]);
    }

    public function premium(): static
    {
        return $this->state(fn() => [
            'role' => UserRole::PREMIUM,
            'credit' => CreditAmount::PREMIUM->value,
        ]);
    }
}
