<?php

namespace App\Services;

use App\Enums\CreditAmount;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreditService
{
    public function rechargeMonthly(): int
    {
        return DB::transaction(function () {

            $updated = 0;

            $updated += User::where('role', UserRole::REGULAR)
                ->update([
                    'credit' => CreditAmount::REGULAR->value,
                ]);

            $updated += User::where('role', UserRole::PREMIUM)
                ->update([
                    'credit' => CreditAmount::PREMIUM->value,
                ]);

            User::where('role', UserRole::OWNER)
                ->update([
                    'credit' => CreditAmount::OWNER->value,
                ]);

            return $updated;
        });
    }

    public function deduct(User $user, int $amount): void
    {
        $user->decrement('credit', $amount);
    }

    public function set(User $user, int $amount): void
    {
        $user->update([
            'credit' => $amount,
        ]);
    }
}