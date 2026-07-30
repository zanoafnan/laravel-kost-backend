<?php

namespace Tests\Feature;

use App\Enums\CreditAmount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_register(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Owner Test',
            'email' => 'owner@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'OWNER',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.role', 'OWNER')
            ->assertJsonPath('data.user.credit', CreditAmount::OWNER->value)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'token',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'role',
                        'credit',
                    ],
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'owner@test.com',
            'role' => 'OWNER',
            'credit' => CreditAmount::OWNER->value,
        ]);
    }

    public function test_regular_user_receives_twenty_credits_after_registration(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Regular User',
            'email' => 'regular@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'REGULAR',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.role', 'REGULAR')
            ->assertJsonPath(
                'data.user.credit',
                CreditAmount::REGULAR->value
            );
    }

    public function test_premium_user_receives_forty_credits_after_registration(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Premium User',
            'email' => 'premium@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'PREMIUM',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.role', 'PREMIUM')
            ->assertJsonPath(
                'data.user.credit',
                CreditAmount::PREMIUM->value
            );
    }

    public function test_registered_user_can_login(): void
    {
        User::factory()
            ->regular()
            ->create([
                'email' => 'regular@test.com',
                'password' => Hash::make('password123'),
            ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'regular@test.com',
            'password' => 'password123',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'token',
                    'user',
                ],
            ]);
    }

    public function test_authenticated_user_can_gwt_profile(): void
    {
        $user = User::factory()->regular()->create();

        $response = $this
            ->actingAs($user, 'sanctum')
            ->getJson('/api/auth/me');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.email', $user->email)
            ->assertJsonPath('data.role', $user->role->value)
            ->assertJsonPath('data.credit', $user->credit);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->regular()->create();

        $token = $user->createToken('test-token');

        $response = $this
            ->withHeader(
                'Authorization',
                'Bearer '.$token->plainTextToken
            )
            ->postJson('/api/auth/logout');

        $response
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseCount(
            'personal_access_tokens',
            0
        );
    }
}