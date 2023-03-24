<?php

namespace App\Repositories\Transaction\Contracts;

use App\Repositories\Base\Contracts\BaseRepositoryContract;
use Illuminate\Database\Eloquent\Collection;

interface TransactionRepositoryContract extends BaseRepositoryContract
{
    /**
     * @param  int  $userId
     * @return Collection
     */
    public function getTransactionsByUserId(int $userId): Collection;

    /**
     * @param  mixed  $filters
     * @return Collection
     */
    public function getTransactions(?array $filters): Collection;
}
