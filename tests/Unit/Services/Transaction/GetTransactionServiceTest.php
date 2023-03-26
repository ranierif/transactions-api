<?php

namespace Tests\Unit\Services\Transaction;

use App\Enums\Status;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Transaction\Contracts\GetTransactionServiceContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetTransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var GetTransactionServiceContract
     */
    private $getTransaction;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->getTransaction = app(GetTransactionServiceContract::class);
    }

    /**
     * @return void
     */
    public function test_get_transactions_from_user_id_and_return_a_collection(): void
    {
        // Arrange
        $user = User::factory()->create();
        Transaction::factory(10)->create([
            'payer_id' => $user->id,
        ]);

        // Act
        $getTransactions = $this->getTransaction
            ->getTransactionsByUserId($user->id);

        // Assert
        $this->assertInstanceOf(
            Collection::class,
            $getTransactions
        );

        $this->assertEquals(
            $getTransactions->count(),
            10
        );
    }

    /**
     * @return void
     */
    public function test_get_transaction_by_id_successfully(): void
    {
        // Arrange
        $transaction = Transaction::factory()->create();

        // Act
        $getTransaction = $this->getTransaction
            ->getTransactionById($transaction->id);

        // Assert
        $this->assertInstanceOf(
            Transaction::class,
            $getTransaction
        );

        $this->assertEquals(
            $getTransaction->id,
            $transaction->id
        );
    }

    /**
     * @return void
     */
    public function test_get_transaction_by_id_not_found(): void
    {
        // Arrange
        $this->expectException(ModelNotFoundException::class);

        // Act
        $getTransaction = $this->getTransaction
            ->getTransactionById(1550);
    }

    /**
     * @return void
     */
    public function test_get_transactions_successfully(): void
    {
        // Arrange
        $transactions = Transaction::factory(10)->create();

        // Act
        $getTransactions = $this->getTransaction
            ->getTransactions([]);

        // Assert
        $this->assertInstanceOf(Collection::class, $getTransactions);

        $this->assertEquals(
            $getTransactions->count(),
            $transactions->count()
        );
    }

    /**
     * @return void
     */
    public function test_get_transactions_successfully_with_filter_by_payer_id(): void
    {
        // Arrange
        $transactions = Transaction::factory(10)->create();
        $firstPayerId = $transactions->first()->payer_id;
        $countTransactionsFromPayerId = Transaction::where('payer_id', $firstPayerId)->count();

        // Act
        $getTransactions = $this->getTransaction
            ->getTransactions([
                'payer_id' => $firstPayerId,
            ]);

        // Assert
        $this->assertInstanceOf(Collection::class, $getTransactions);

        $this->assertEquals(
            $getTransactions->count(),
            $countTransactionsFromPayerId
        );
    }

    /**
     * @return void
     */
    public function test_get_transactions_successfully_with_filter_by_payee_id(): void
    {
        // Arrange
        $transactions = Transaction::factory(10)->create();
        $firstPayeeId = $transactions->first()->payee_id;
        $countTransactionsFromPayeeId = Transaction::where('payee_id', $firstPayeeId)->count();

        // Act
        $getTransactions = $this->getTransaction
            ->getTransactions([
                'payee_id' => $firstPayeeId,
            ]);

        // Assert
        $this->assertInstanceOf(
            Collection::class,
            $getTransactions
        );

        $this->assertEquals(
            $getTransactions->count(),
            $countTransactionsFromPayeeId
        );
    }

    /**
     * @return void
     */
    public function test_get_transactions_successfully_with_filter_by_status_id(): void
    {
        // Arrange
        Transaction::factory(10)->create();
        $countTransactionsFromStatusId = Transaction::where('status_id', Status::COMPLETE->value)->count();

        // Act
        $getTransactions = $this->getTransaction
            ->getTransactions([
                'status_id' => Status::COMPLETE->value,
            ]);

        // Assert
        $this->assertInstanceOf(
            Collection::class,
            $getTransactions
        );

        $this->assertEquals(
            $getTransactions->count(),
            $countTransactionsFromStatusId
        );
    }
}
