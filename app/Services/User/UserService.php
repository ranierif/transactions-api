<?php

namespace App\Services\User;

use App\Models\User;
use App\Repositories\User\Contracts\UserRepositoryContract;
use App\Services\User\Contracts\UserServiceContract;
use Illuminate\Support\Collection;

class UserService implements UserServiceContract
{
    /**
     * @param  UserRepositoryContract  $userRepository
     */
    public function __construct(
        protected UserRepositoryContract $userRepository
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function getUsers(?array $filters): Collection
    {
        return $this->userRepository->getUsers($filters);
    }

    /**
     * {@inheritDoc}
     */
    public function findUserById(int $userId): ?User
    {
        return $this->userRepository->findBy('id', $userId);
    }

    /**
     * {@inheritDoc}
     */
    public function removeBalance(int $userId, int $value): bool
    {
        return $this->userRepository->removeBalance($userId, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function addBalance(int $userId, int $value): bool
    {
        return $this->userRepository->addBalance($userId, $value);
    }
}
