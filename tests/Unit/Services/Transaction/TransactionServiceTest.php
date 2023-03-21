<?php

namespace Tests\Unit\Services\Transaction;

use App\Models\Transaction;
use App\Models\User;
use App\Services\Transaction\Contracts\TransactionServiceContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var TransactionServiceContract
     */
    private $transactionService;

    public function setUp(): void
    {
        parent::setUp();

        $this->transactionService = app(TransactionServiceContract::class);
    }

    /**
     * @return void
     */
    public function test_get_users_from_service_return_a_collection(): void
    {
        // Arrange
        $user = User::factory()->create();
        Transaction::factory(10)->create([
            'payer_id' => $user->id,
        ]);

        // Act
        $getUsers = $this->transactionService->getTransactionsByUserId($user->id);

        // Assert
        $this->assertInstanceOf(Collection::class, $getUsers);
        $this->assertEquals($getUsers->count(), 10);
    }
}
