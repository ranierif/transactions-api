<?php

namespace Tests\Unit\Services\Transaction;

use App\Enums\Status;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Transaction\Contracts\StoreTransactionServiceContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_store_transaction_complete_status_successfully(): void
    {
        // Arrange
        $userPerson = User::factory()->create(['document_type_id' => 1]);
        $userCompany = User::factory()->create(['document_type_id' => 2]);
        $params = [
            'payer_id' => $userPerson->id,
            'payee_id' => $userCompany->id,
            'value' => $userPerson->balance,
            'status_id' => Status::COMPLETE->value,
        ];

        // Act
        $transaction = app(StoreTransactionServiceContract::class)
            ->storeTransaction(
                $params['payer_id'],
                $params['payee_id'],
                $params['value'],
                $params['status_id'],
            );

        // Assert
        $this->assertInstanceOf(Transaction::class, $transaction);

        $this->assertDatabaseHas(Transaction::class, [
            'payer_id' => $params['payer_id'],
            'payee_id' => $params['payee_id'],
            'value' => $params['value'],
            'status_id' => $params['status_id'],
        ]);
    }

    /**
     * @return void
     */
    public function test_store_transaction_chargeback_status_successfully(): void
    {
        // Arrange
        $userPerson = User::factory()->create(['document_type_id' => 1]);
        $userCompany = User::factory()->create(['document_type_id' => 2]);
        $params = [
            'payer_id' => $userPerson->id,
            'payee_id' => $userCompany->id,
            'value' => $userPerson->balance,
            'status_id' => Status::CHARGEBACK->value,
        ];

        // Act
        $transaction = app(StoreTransactionServiceContract::class)
            ->storeTransaction(
                $params['payer_id'],
                $params['payee_id'],
                $params['value'],
                $params['status_id'],
            );

        // Assert
        $this->assertInstanceOf(Transaction::class, $transaction);

        $this->assertDatabaseHas(Transaction::class, [
            'payer_id' => $params['payer_id'],
            'payee_id' => $params['payee_id'],
            'value' => $params['value'],
            'status_id' => $params['status_id'],
        ]);
    }
}
