<?php

namespace App\Services;

use App\Enums\CreditAmount;
use App\Enums\UserRole;
use App\Models\AvailabilityRequest;
use App\Models\Kost;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AvailabilityService
{
    public function __construct(
        private CreditService $creditService
    ) {}

    public function ask(User $user, Kost $kost): AvailabilityRequest

    {
        if ($user->role === UserRole::OWNER) {
            throw new AccessDeniedHttpException(
                'Owner cannot ask room availability.'
            );
        }

        if ($user->credit < CreditAmount::ASK_AVAILABILITY->value) {
            throw ValidationException::withMessages([
                'credit' => ['Not enough credit.'],
            ]);
        }

        return DB::transaction(function () use ($user, $kost) {

            $this->creditService->deduct(
                $user,
                CreditAmount::ASK_AVAILABILITY->value
            );

            return AvailabilityRequest::create([
                'user_id' => $user->id,
                'kost_id' => $kost->id,
                'credit_used' => CreditAmount::ASK_AVAILABILITY->value,
            ]);
        });
    }
}
