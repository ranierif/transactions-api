<?php

namespace Tests\Unit\Services\Transaction;

use App\Enums\Status;
use App\Models\Transaction;
use App\Services\Transaction\Contracts\UpdateTransactionServiceContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var UpdateTransactionServiceContract
     */
    private $updateTransaction;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->updateTransaction = app(UpdateTransactionServiceContract::class);
    }

    /**
     * @return void
     */
    public function test_update_transaction_status(): void
    {
        // Arrange
        $transaction = Transaction::factory()->create([
            'status_id' => Status::COMPLETE->value,
        ]);

        // Act
        $updateStatus = $this->updateTransaction
            ->updateStatus(
                $transaction->id,
                Status::CHARGEBACK->value
            );

        // Assert
        $this->assertIsBool($updateStatus);

        $this->assertDatabaseHas(Transaction::class, [
            'id' => $transaction->id,
            'status_id' => Status::CHARGEBACK->value,
        ]);
    }
}
