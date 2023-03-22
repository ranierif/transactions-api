<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\Base\BaseRepositoryEloquent;
use App\Repositories\User\Contracts\UserRepositoryContract;
use Illuminate\Support\Collection;

class UserRepositoryEloquent extends BaseRepositoryEloquent implements UserRepositoryContract
{
    /**
     * @var User
     */
    protected $model = User::class;

    /**
     * {@inheritDoc}
     */
    public function getUsers(?array $filters): Collection
    {
        $users = $this->model::with('documentType');

        if (! empty($filters)) {
            foreach ($filters as $key => $value) {
                $users->where($key, $value);
            }
        }

        return $users->get();
    }

    /**
     * {@inheritDoc}
     */
    public function removeBalance(int $userId, int $value): bool
    {
        return $this->model::where('id', $userId)
            ->decrement('balance', $value);
    }

    /**
     * {@inheritDoc}
     */
    public function addBalance(int $userId, int $value): bool
    {
        return $this->model::where('id', $userId)
            ->increment('balance', $value);
    }
}
