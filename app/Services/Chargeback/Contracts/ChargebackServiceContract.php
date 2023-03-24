<?php

namespace App\Services\Chargeback\Contracts;

use App\Models\Chargeback;

interface ChargebackServiceContract
{
    /**
     * @param  int  $transactionId
     * @param  null|string $reason
     * @return Chargeback
     */
    public function handleChargeback(int $transactionId, ?string $reason): Chargeback;
}
