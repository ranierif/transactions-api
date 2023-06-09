<?php

namespace App\Repositories\Transaction;

use App\Models\Transaction;
use App\Repositories\Base\BaseRepositoryEloquent;
use App\Repositories\Transaction\Contracts\TransactionRepositoryContract;
use Illuminate\Database\Eloquent\Collection;

class TransactionRepositoryEloquent extends BaseRepositoryEloquent implements TransactionRepositoryContract
{
    /**
     * @var Transaction
     */
    protected $model = Transaction::class;

    /**
     * {@inheritDoc}
     */
    public function getTransactionsByUserId(int $userId): Collection
    {
        return $this->model::orderBy('created_at', 'DESC')
            ->where(function ($query) use ($userId) {
                $query->where('payee_id', $userId)
                ->orWhere('payer_id', $userId);
            })
            ->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getTransactions(?array $filters): Collection
    {
        $transactions = $this->model::orderBy('created_at', 'DESC');

        if (! empty($filters)) {
            foreach ($filters as $key => $value) {
                $transactions->where($key, $value);
            }
        }

        return $transactions->get();
    }
}
