<?php

namespace Tests\Unit\Services\Chargeback;

use App\Models\Chargeback;
use App\Models\Transaction;
use App\Services\Chargeback\Contracts\StoreChargebackServiceContract;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreChargebackServiceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @var StoreChargebackServiceContract
     */
    private $storeChargeback;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->storeChargeback = app(StoreChargebackServiceContract::class);
    }

    /**
     * @return void
     */
    public function test_can_store_chargeback_with_reason_successfully(): void
    {
        // Arrange
        $transactionOrigin = Transaction::factory()->create();
        $transactionReversal = Transaction::factory()->create();
        $reason = $this->faker()->text(200);

        // Act
        $transaction = $this->storeChargeback
            ->store(
                $transactionOrigin->id,
                $transactionReversal->id,
                $reason,
            );

        // Assert
        $this->assertInstanceOf(Chargeback::class, $transaction);

        $this->assertDatabaseHas(Chargeback::class, [
            'origin_transaction_id' => $transactionOrigin->id,
            'reversal_transaction_id' => $transactionReversal->id,
            'reason' => $reason,
        ]);
    }

    /**
     * @return void
     */
    public function test_can_store_chargeback_without_reason_successfully(): void
    {
        // Arrange
        $transactionOrigin = Transaction::factory()->create();
        $transactionReversal = Transaction::factory()->create();

        // Act
        $transaction = $this->storeChargeback
            ->store(
                $transactionOrigin->id,
                $transactionReversal->id,
            );

        // Assert
        $this->assertInstanceOf(Chargeback::class, $transaction);

        $this->assertDatabaseHas(Chargeback::class, [
            'origin_transaction_id' => $transactionOrigin->id,
            'reversal_transaction_id' => $transactionReversal->id,
            'reason' => null,
        ]);
    }

    /**
     * @return void
     */
    public function test_cannot_store_chargeback_with_inexistent_origin_transaction(): void
    {
        // Arrange
        $this->expectException(QueryException::class);
        $transactionReversal = Transaction::factory()->create();

        // Act
        $this->storeChargeback
            ->store(
                100,
                $transactionReversal->id,
            );
    }

    /**
     * @return void
     */
    public function test_cannot_store_chargeback_with_inexistent_reversal_transaction(): void
    {
        // Arrange
        $this->expectException(QueryException::class);
        $transactionOrigin = Transaction::factory()->create();

        // Act
        $this->storeChargeback
            ->store(
                $transactionOrigin->id,
                100,
            );
    }
}
