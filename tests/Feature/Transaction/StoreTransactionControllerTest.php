<?php

namespace Tests\Feature\Transaction;

use App\Enums\Status;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Transaction\Contracts\TransactionServiceContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
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
    public function test_store_new_transaction_from_person_user_to_company_user_successfully(): void
    {
        // Arrange
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
}
