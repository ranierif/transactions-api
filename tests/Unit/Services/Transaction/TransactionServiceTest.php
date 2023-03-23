<?php

namespace Tests\Unit\Services\Transaction;

use App\Enums\Status;
use App\Exceptions\Authorization\UnauthorizedToStoreTransactionException;
use App\Exceptions\Notification\NotificationToPayeeNotSendedException;
use App\Exceptions\Transaction\InsufficientFundsToSendTransactionException;
use App\Exceptions\Transaction\PayerCannotSendTransactionsException;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Authorization\AuthorizationService;
use App\Services\Authorization\Contracts\AuthorizationServiceContract;
use App\Services\Notification\Contracts\NotificationServiceContract;
use App\Services\Notification\NotificationService;
use App\Services\Transaction\Contracts\TransactionServiceContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;

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
        $getTransactions = app(TransactionServiceContract::class)->getTransactionsByUserId($user->id);

        // Assert
        $this->assertInstanceOf(Collection::class, $getTransactions);
        $this->assertEquals($getTransactions->count(), 10);
    }

    /**
     * @return void
     */
    public function test_can_store_new_transaction_in_service(): void
    {
        // Arrange
        $this->mockAuthorizationServiceSuccess();
        $this->mockNotificationServiceSuccess();
        $userPerson = User::factory()->create(['document_type_id' => 1]);
        $userCompany = User::factory()->create(['document_type_id' => 2]);
        $value = 100;

        // Act
        $transaction = app(TransactionServiceContract::class)
            ->handleNewTransaction(
                $userPerson->id,
                $userCompany->id,
                $value
            );

        // Assert
        $this->assertInstanceOf(Transaction::class, $transaction);

        $this->assertDatabaseHas(Transaction::class, [
            'payer_id' => $userPerson->id,
            'payee_id' => $userCompany->id,
            'value' => $value,
            'status_id' => Status::COMPLETE->value,
        ]);

        $this->assertDatabaseHas(User::class, [
            'id' => $userPerson->id,
            'balance' => ($userPerson->balance - $value),
        ]);

        $this->assertDatabaseHas(User::class, [
            'id' => $userCompany->id,
            'balance' => ($userCompany->balance + $value),
        ]);
    }

    /**
     * @return void
     */
    public function test_cannot_store_new_transaction_from_user_not_valid_to_send_transaction_exception(): void
    {
        // Arrange
        $this->expectException(PayerCannotSendTransactionsException::class);
        $userPerson = User::factory()->create(['document_type_id' => 1]);
        $userCompany = User::factory()->create(['document_type_id' => 2]);
        $value = 100;

        // Act
        app(TransactionServiceContract::class)
            ->handleNewTransaction(
                $userCompany->id,
                $userPerson->id,
                $value
            );
    }

    /**
     * @return void
     */
    public function test_cannot_store_new_transaction_with_insufficient_funds_exception(): void
    {
        // Arrange
        $this->expectException(InsufficientFundsToSendTransactionException::class);
        $userPerson = User::factory()->create(['document_type_id' => 1]);
        $userCompany = User::factory()->create(['document_type_id' => 2]);
        $value = $userPerson->balance + 100;

        // Act
        app(TransactionServiceContract::class)
            ->handleNewTransaction(
                $userPerson->id,
                $userCompany->id,
                $value
            );
    }

    /**
     * @return void
     */
    public function test_cannot_store_new_transaction_when_is_unauthorized_exception(): void
    {
        // Arrange
        $this->mockAuthorizationServiceError();
        $this->expectException(UnauthorizedToStoreTransactionException::class);
        $userPerson = User::factory()->create(['document_type_id' => 1]);
        $userCompany = User::factory()->create(['document_type_id' => 2]);
        $value = 100;

        // Act
        app(TransactionServiceContract::class)
            ->handleNewTransaction(
                $userPerson->id,
                $userCompany->id,
                $value
            );
    }

    /**
     * @return void
     */
    public function test_cannot_store_new_transaction_when_not_send_notification_exception(): void
    {
        // Arrange
        $this->mockAuthorizationServiceSuccess();
        $this->mockNotificationServiceError();
        $this->expectException(NotificationToPayeeNotSendedException::class);
        $userPerson = User::factory()->create(['document_type_id' => 1]);
        $userCompany = User::factory()->create(['document_type_id' => 2]);
        $value = 100;

        // Act
        app(TransactionServiceContract::class)
            ->handleNewTransaction(
                $userPerson->id,
                $userCompany->id,
                $value
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
        $getTransaction = app(TransactionServiceContract::class)->getTransactionById($transaction->id);

        // Assert
        $this->assertInstanceOf(Transaction::class, $getTransaction);

        $this->assertEquals($getTransaction->id, $transaction->id);
    }

    /**
     * @return void
     */
    private function mockAuthorizationServiceSuccess(): void
    {
        $this->instance(
            AuthorizationServiceContract::class,
            Mockery::mock(AuthorizationService::class, function (MockInterface $mock) {
                return $mock->shouldReceive('verify')
                    ->andReturn($this->mockAuthorizationResponseSuccess());
            })
        );
    }

    /**
     * @return array
     */
    private function mockAuthorizationResponseSuccess(): array
    {
        return [
            'success' => true,
            'message' => 'Autorizado',
        ];
    }

    /**
     * @return void
     */
    private function mockAuthorizationServiceError(): void
    {
        $this->instance(
            AuthorizationServiceContract::class,
            Mockery::mock(AuthorizationService::class, function (MockInterface $mock) {
                return $mock->shouldReceive('verify')
                    ->andReturn($this->mockAuthorizationResponseError());
            })
        );
    }

    /**
     * @return array
     */
    private function mockAuthorizationResponseError(): array
    {
        return [
            'success' => false,
            'message' => 'Fake error message',
        ];
    }

    /**
     * @return void
     */
    private function mockNotificationServiceSuccess(): void
    {
        $this->instance(
            NotificationServiceContract::class,
            Mockery::mock(NotificationService::class, function (MockInterface $mock) {
                return $mock->shouldReceive('send')
                    ->andReturn($this->mockNotificationResponseSuccess());
            })
        );
    }

    /**
     * @return array
     */
    private function mockNotificationResponseSuccess(): array
    {
        return [
            'success' => true,
            'message' => 'Success',
        ];
    }

    /**
     * @return void
     */
    private function mockNotificationServiceError(): void
    {
        $this->instance(
            NotificationServiceContract::class,
            Mockery::mock(NotificationService::class, function (MockInterface $mock) {
                return $mock->shouldReceive('send')
                    ->andReturn($this->mockNotificationResponseError());
            })
        );
    }

    /**
     * @return array
     */
    private function mockNotificationResponseError(): array
    {
        return [
            'success' => false,
            'message' => 'Fake error message',
        ];
    }
}
