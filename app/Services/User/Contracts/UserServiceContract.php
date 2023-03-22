<?php

namespace App\Services\User\Contracts;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserServiceContract
{
    /**
     * @param  null|array  $filters
     * @return Collection
     */
    public function getUsers(?array $filters): Collection;

    /**
     * @param  int  $userId
     * @return User|null
     */
    public function findUserById(int $userId): ?User;

    /**
     * @param  int  $userId
     * @return bool
     */
    public function removeBalance(int $userId, int $value): bool;

    /**
     * @param  int  $userId
     * @return bool
     */
    public function addBalance(int $userId, int $value): bool;
}
