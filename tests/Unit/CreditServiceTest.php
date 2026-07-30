<?php

namespace Tests\Unit;

use App\Enums\CreditAmount;
use App\Models\User;
use App\Services\CreditService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreditServiceTest extends TestCase
{
    use RefreshDatabase;

    private CreditService $creditService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->creditService = app(CreditService::class);
    }

    public function test_deduct_credit(): void
    {
        $user = User::factory()->regular()->create();

        $this->creditService->deduct(
            $user,
            CreditAmount::ASK_AVAILABILITY->value
        );

        $this->assertEquals(
            15,
            $user->fresh()->credit
        );
    }

    public function test_set_credit(): void
    {
        $user = User::factory()->regular()->create([
            'credit' => 3,
        ]);

        $this->creditService->set(
            $user,
            20
        );

        $this->assertEquals(
            20,
            $user->fresh()->credit
        );
    }

    public function test_recharge_monthly_credit(): void
    {
        $owner = User::factory()->owner()->create([
            'credit' => 99,
        ]);

        $regular = User::factory()->regular()->create([
            'credit' => 2,
        ]);

        $premium = User::factory()->premium()->create([
            'credit' => 7,
        ]);

        $updated = $this->creditService->rechargeMonthly();

        $this->assertEquals(2, $updated);

        $this->assertEquals(
            CreditAmount::OWNER->value,
            $owner->fresh()->credit
        );

        $this->assertEquals(
            CreditAmount::REGULAR->value,
            $regular->fresh()->credit
        );

        $this->assertEquals(
            CreditAmount::PREMIUM->value,
            $premium->fresh()->credit
        );
    }
}