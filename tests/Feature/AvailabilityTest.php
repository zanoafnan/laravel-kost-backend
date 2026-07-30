<?php

namespace Tests\Feature;

use App\Enums\CreditAmount;
use App\Models\Kost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailabilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_user_can_ask_availability(): void
    {
        $user = User::factory()->regular()->create();

        $kost = Kost::factory()->create();

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/availability', [
                'kost_id' => $kost->id,
            ]);

        $response->assertCreated();

        $this->assertDatabaseHas('availability_requests', [
            'user_id' => $user->id,
            'kost_id' => $kost->id,
            'credit_used' => CreditAmount::ASK_AVAILABILITY->value,
        ]);

        $this->assertEquals(
            CreditAmount::REGULAR->value - CreditAmount::ASK_AVAILABILITY->value,
            $user->fresh()->credit
        );
    }

    public function test_premium_user_can_ask_availability(): void
    {
        $user = User::factory()->premium()->create();

        $kost = Kost::factory()->create();

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/availability', [
                'kost_id' => $kost->id,
            ]);

        $response->assertCreated();

        $this->assertEquals(
            CreditAmount::PREMIUM->value - CreditAmount::ASK_AVAILABILITY->value,
            $user->fresh()->credit
        );
    }

    public function test_owner_cannot_ask_availability(): void
    {
        $owner = User::factory()->owner()->create();

        $kost = Kost::factory()->create();

        $response = $this
            ->actingAs($owner, 'sanctum')
            ->postJson('/api/availability', [
                'kost_id' => $kost->id,
            ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('availability_requests', [
            'user_id' => $owner->id,
            'kost_id' => $kost->id,
        ]);
    }

    public function test_user_cannot_ask_availability_if_credit_is_insufficient(): void
    {
        $user = User::factory()->regular()->create([
            'credit' => 3,
        ]);

        $kost = Kost::factory()->create();

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/availability', [
                'kost_id' => $kost->id,
            ]);

        $response->assertStatus(422);

        $this->assertDatabaseMissing('availability_requests', [
            'user_id' => $user->id,
            'kost_id' => $kost->id,
        ]);

        $this->assertEquals(
            3,
            $user->fresh()->credit
        );
    }

    public function test_guest_cannot_ask_availability(): void
    {
        $kost = Kost::factory()->create();

        $this->postJson('/api/availability', [
            'kost_id' => $kost->id,
        ])->assertUnauthorized();
    }
}