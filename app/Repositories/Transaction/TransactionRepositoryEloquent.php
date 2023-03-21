<?php

namespace App\Repositories\Transactions;

use App\Models\Transaction;
use App\Repositories\Base\BaseRepositoryEloquent;
use App\Repositories\Transaction\Contracts\TransactionRepositoryContract;

class TransactionRepositoryEloquent extends BaseRepositoryEloquent implements TransactionRepositoryContract
{
    /**
     * @var Transaction
     */
    protected $model = Transaction::class;
}
