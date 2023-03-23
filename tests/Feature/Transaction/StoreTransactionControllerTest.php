<?php

namespace Tests\Feature\Transaction;

use App\Enums\Status;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Authorization\AuthorizationService;
use App\Services\Authorization\Contracts\AuthorizationServiceContract;
use App\Services\Transaction\Contracts\TransactionServiceContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class StoreTransactionControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @var string
     */
    private const ROUTE_NAME = 'transactions.store';

    /**
     * @var TransactionServiceContract
     */
    private $transactionService;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->transactionService = app()->make(TransactionServiceContract::class);
    }

    /**
     * @return void
     */
    public function test_can_store_new_transaction_from_person_user_to_company_user(): void
    {
        // Arrange
        $this->mockAuthorizationServiceSuccess();
        $userPerson = User::factory()->create(['document_type_id' => 1]);
        $userCompany = User::factory()->create(['document_type_id' => 2]);
        $payload = [
            'payer_id' => $userPerson->id,
            'payee_id' => $userCompany->id,
            'value' => 100,
        ];

        // Act
        $response = $this->postJson(
            route(self::ROUTE_NAME),
            $payload
        );

        // Assert
        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas(Transaction::class, [
            'payer_id' => Arr::get($payload, 'payer_id'),
            'payee_id' => Arr::get($payload, 'payee_id'),
            'value' => Arr::get($payload, 'value'),
            'status_id' => Status::COMPLETE->value,
        ]);

        $this->assertDatabaseHas(User::class, [
            'id' => $userPerson->id,
            'balance' => ($userPerson->balance - Arr::get($payload, 'value')),
        ]);

        $this->assertDatabaseHas(User::class, [
            'id' => $userCompany->id,
            'balance' => ($userCompany->balance + Arr::get($payload, 'value')),
        ]);
    }

    /**
     * @return void
     */
    public function test_cannot_store_new_transaction_from_company_user_to_person_user(): void
    {
        // Arrange
        $userPerson = User::factory()->create(['document_type_id' => 1]);
        $userCompany = User::factory()->create(['document_type_id' => 2]);
        $payload = [
            'payer_id' => $userCompany->id,
            'payee_id' => $userPerson->id,
            'value' => 100,
        ];

        // Act
        $response = $this->postJson(
            route(self::ROUTE_NAME),
            $payload
        );

        // Assert
        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertJson([
            'message' => 'The payer selected cannot send transactions',
        ]);

        $this->assertDatabaseMissing(Transaction::class, [
            'payer_id' => Arr::get($payload, 'payer_id'),
            'payee_id' => Arr::get($payload, 'payee_id'),
            'value' => Arr::get($payload, 'value'),
            'status_id' => Status::COMPLETE->value,
        ]);
    }

    /**
     * @return void
     */
    public function test_cannot_store_new_transaction_with_insufficient_funds(): void
    {
        // Arrange
        $userPerson = User::factory()->create(['document_type_id' => 1]);
        $userCompany = User::factory()->create(['document_type_id' => 2]);
        $payload = [
            'payer_id' => $userPerson->id,
            'payee_id' => $userCompany->id,
            'value' => $userPerson->balance + 100,
        ];

        // Act
        $response = $this->postJson(
            route(self::ROUTE_NAME),
            $payload
        );

        // Assert
        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertJson([
            'message' => 'Insufficient fund to send transaction',
        ]);

        $this->assertDatabaseMissing(Transaction::class, [
            'payer_id' => Arr::get($payload, 'payer_id'),
            'payee_id' => Arr::get($payload, 'payee_id'),
            'value' => Arr::get($payload, 'value'),
            'status_id' => Status::COMPLETE->value,
        ]);
    }

    /**
     * @return void
     */
    public function test_cannot_store_new_transaction_when_is_unauthorized(): void
    {
        // Arrange
        $this->mockAuthorizationServiceError();
        $userPerson = User::factory()->create(['document_type_id' => 1]);
        $userCompany = User::factory()->create(['document_type_id' => 2]);
        $payload = [
            'payer_id' => $userPerson->id,
            'payee_id' => $userCompany->id,
            'value' => $userPerson->balance,
        ];

        // Act
        $response = $this->postJson(
            route(self::ROUTE_NAME),
            $payload
        );

        // Assert
        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertJson([
            'message' => 'Unauthorized to store transaction',
        ]);

        $this->assertDatabaseMissing(Transaction::class, [
            'payer_id' => Arr::get($payload, 'payer_id'),
            'payee_id' => Arr::get($payload, 'payee_id'),
            'value' => Arr::get($payload, 'value'),
            'status_id' => Status::COMPLETE->value,
        ]);
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
}
