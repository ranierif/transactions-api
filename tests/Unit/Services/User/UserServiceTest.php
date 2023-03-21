<?php

namespace Tests\Unit\Services\User;

use App\Services\User\Contracts\UserServiceContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var UserServiceContract
     */
    private $userService;

    public function setUp(): void
    {
        parent::setUp();

        $this->userService = app(UserServiceContract::class);
    }

    /**
     * @return void
     */
    public function test_get_users_from_service_return_a_collection(): void
    {
        // Act
        $getUsers = $this->userService->getUsers([]);

        // Assert
        $this->assertInstanceOf(Collection::class, $getUsers);
    }
}
