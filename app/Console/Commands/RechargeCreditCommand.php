<?php

namespace App\Console\Commands;

use App\Services\CreditService;
use Illuminate\Console\Command;

class RechargeCreditCommand extends Command
{
    protected $signature = 'credit:recharge';

    protected $description = 'Recharge monthly credit for regular and premium users';

    public function __construct(
        private CreditService $creditService
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $updated = $this->creditService->rechargeMonthly();

        $this->info("Successfully recharged {$updated} user(s).");

        return self::SUCCESS;
    }
}