<?php

namespace Tests\Unit\Services\User;

use App\Models\User;
use App\Services\User\Contracts\UserServiceContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var UserServiceContract
     */
    private $userService;

    /**
     * @return void
     */
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

    /**
     * @return void
     */
    public function test_find_user_by_id_from_service_return_the_user_model(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $findUser = $this->userService->findUserById($user->id);

        // Assert
        $this->assertInstanceOf(User::class, $findUser);
    }

    /**
     * @return void
     */
    public function test_not_find_user_by_id_and_return_exception(): void
    {
        // Arrange
        $this->expectException(ModelNotFoundException::class);

        // Act
        $findUser = $this->userService->findUserById(100);
    }

    /**
     * @return void
     */
    public function test_add_balance_to_user_successfully(): void
    {
        // Arrange
        $user = User::factory()->create([
            'balance' => 1000,
        ]);

        // Act
        $addBalance = $this->userService->addBalance($user->id, 100);

        // Assert
        $this->assertTrue($addBalance);

        $this->assertDatabaseHas(User::class, [
            'id' => $user->id,
            'balance' => ($user->balance + 100),
        ]);
    }

    /**
     * @return void
     */
    public function test_add_balance_to_user_return_false_when_is_inexistent_user(): void
    {
        // Act
        $addBalance = $this->userService->addBalance(
            userId: 100,
            value: 100
        );

        // Assert
        $this->assertFalse($addBalance);
    }

    /**
     * @return void
     */
    public function test_remove_balance_to_user_successfully(): void
    {
        // Arrange
        $user = User::factory()->create([
            'balance' => 1000,
        ]);

        // Act
        $addBalance = $this->userService->removeBalance($user->id, 100);

        // Assert
        $this->assertTrue($addBalance);

        $this->assertDatabaseHas(User::class, [
            'id' => $user->id,
            'balance' => ($user->balance - 100),
        ]);
    }

    /**
     * @return void
     */
    public function test_remove_balance_to_user_return_false_when_is_inexistent_user(): void
    {
        // Act
        $addBalance = $this->userService->removeBalance(
            userId: 100,
            value: 100
        );

        // Assert
        $this->assertFalse($addBalance);
    }
}
