<?php

namespace App\Services\Chargeback;

use App\Models\Chargeback;
use App\Repositories\Chargeback\Contracts\ChargebackRepositoryContract;
use App\Services\Chargeback\Contracts\StoreChargebackServiceContract;

class StoreChargebackService implements StoreChargebackServiceContract
{
    /**
     * @param  ChargebackRepositoryContract  $chargebackRepository
     */
    public function __construct(
        protected ChargebackRepositoryContract $chargebackRepository
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function storeChargeback(int $originId, int $reversalId, ?string $reason): ?Chargeback
    {
        return $this->chargebackRepository->store([
            'origin_transaction_id' => $originId,
            'reversal_transaction_id' => $reversalId,
            'reason' => $reason,
        ]);
    }
}
