<?php

namespace App\Services\User;

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
}
