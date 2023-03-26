<?php

namespace Tests\Unit\Services\Transaction;

use App\Enums\Status;
use App\Exceptions\Chargeback\TransactionAlreadyHasChargebackException;
use App\Models\Chargeback;
use App\Models\Transaction;
use App\Services\Chargeback\Contracts\ChargebackServiceContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChargebackServiceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @return void
     */
    public function test_can_store_new_chargeback_with_reason(): void
    {
        // Arrange
        $transaction = Transaction::factory()->create([
            'value' => 500,
            'status_id' => Status::COMPLETE->value,
        ]);
        $reason = $this->faker()->text(200);

        // Act
        $chargeback = app(ChargebackServiceContract::class)
            ->handleChargeback(
                $transaction->id,
                $reason,
            );

        // Assert
        $this->assertInstanceOf(Chargeback::class, $chargeback);

        $this->assertDatabaseHas(Chargeback::class, [
            'origin_transaction_id' => $transaction->id,
            'reason' => $reason,
        ]);

        $this->assertDatabaseHas(Transaction::class, [
            'payer_id' => $transaction->payer_id,
            'payee_id' => $transaction->payee_id,
            'value' => $transaction->value,
            'status_id' => Status::CHARGEBACK->value,
        ]);

        $this->assertDatabaseHas(Transaction::class, [
            'payer_id' => $transaction->payee_id,
            'payee_id' => $transaction->payer_id,
            'value' => $transaction->value,
            'status_id' => Status::COMPLETE->value,
        ]);
    }

    /**
     * @return void
     */
    public function test_can_store_new_chargeback_without_reason(): void
    {
        // Arrange
        $transaction = Transaction::factory()->create([
            'value' => 500,
            'status_id' => Status::COMPLETE->value,
        ]);

        // Act
        $chargeback = app(ChargebackServiceContract::class)
            ->handleChargeback(
                $transaction->id,
            );

        // Assert
        $this->assertInstanceOf(Chargeback::class, $chargeback);

        $this->assertDatabaseHas(Chargeback::class, [
            'origin_transaction_id' => $transaction->id,
            'reason' => null,
        ]);

        $this->assertDatabaseHas(Transaction::class, [
            'payer_id' => $transaction->payer_id,
            'payee_id' => $transaction->payee_id,
            'value' => $transaction->value,
            'status_id' => Status::CHARGEBACK->value,
        ]);

        $this->assertDatabaseHas(Transaction::class, [
            'payer_id' => $transaction->payee_id,
            'payee_id' => $transaction->payer_id,
            'value' => $transaction->value,
            'status_id' => Status::COMPLETE->value,
        ]);
    }

    /**
     * @return void
     */
    public function test_cannot_store_chargeback_when_transaction_already_is_chargeback(): void
    {
        // Arrange
        $this->expectException(TransactionAlreadyHasChargebackException::class);
        $transaction = Transaction::factory()->create([
            'value' => 500,
            'status_id' => Status::CHARGEBACK->value,
        ]);
        $reason = $this->faker()->text(200);

        // Act
        app(ChargebackServiceContract::class)
            ->handleChargeback(
                $transaction->id,
                $reason,
            );
    }
}
