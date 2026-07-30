<?php

namespace Tests\Feature;

use App\Models\Kost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KostTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_create_kost(): void
    {
        $owner = User::factory()->owner()->create();

        $response = $this
            ->actingAs($owner, 'sanctum')
            ->postJson('/api/owner/kosts', [
                'name' => 'Kost Mawar',
                'description' => 'Near campus',
                'location' => 'Semarang',
                'price' => 1000000,
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.name', 'Kost Mawar');

        $this->assertDatabaseHas('kosts', [
            'name' => 'Kost Mawar',
            'owner_id' => $owner->id,
        ]);
    }

    public function test_regular_user_cannot_create_kost(): void
    {
        $user = User::factory()->regular()->create();

        $response = $this
            ->actingAs($user, 'sanctum')
            ->postJson('/api/owner/kosts', [
                'name' => 'Kost Mawar',
                'description' => 'Near campus',
                'location' => 'Semarang',
                'price' => 1000000,
            ]);

        $response->assertForbidden();
    }

    public function test_owner_can_update_own_kost(): void
    {
        $owner = User::factory()->owner()->create();

        $kost = Kost::factory()->create([
            'owner_id' => $owner->id,
        ]);

        $response = $this
            ->actingAs($owner, 'sanctum')
            ->putJson("/api/owner/kosts/{$kost->id}", [
                'name' => 'Updated Kost',
                'description' => 'Updated description',
                'location' => 'Jakarta',
                'price' => 2000000,
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated Kost');

        $this->assertDatabaseHas('kosts', [
            'id' => $kost->id,
            'name' => 'Updated Kost',
        ]);
    }

    public function test_owner_can_delete_own_kost(): void
    {
        $owner = User::factory()->owner()->create();

        $kost = Kost::factory()->create([
            'owner_id' => $owner->id,
        ]);

        $response = $this
            ->actingAs($owner, 'sanctum')
            ->deleteJson("/api/owner/kosts/{$kost->id}");

        $response->assertOk();

        $this->assertSoftDeleted('kosts', [
            'id' => $kost->id,
        ]);
    }

    public function test_user_can_search_kost(): void
    {
        Kost::factory()->create([
            'name' => 'Kost Semarang',
            'location' => 'Semarang',
        ]);

        $response = $this->getJson(
            '/api/kosts?location=Semarang'
        );

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_user_can_view_kost_detail(): void
    {
        $kost = Kost::factory()->create();

        $response = $this->getJson(
            "/api/kosts/{$kost->id}"
        );

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $kost->id);
    }
}