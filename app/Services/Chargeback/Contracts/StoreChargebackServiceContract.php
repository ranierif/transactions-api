<?php

namespace App\Services\Chargeback\Contracts;

use App\Models\Chargeback;

interface StoreChargebackServiceContract
{
    /**
     * @param  int $originId
     * @param  int $reversalId
     * @param  null|string $reason
     * @return null|Chargeback
     */
    public function storeChargeback(int $originId, int $reversalId, ?string $reason): ?Chargeback;
}
