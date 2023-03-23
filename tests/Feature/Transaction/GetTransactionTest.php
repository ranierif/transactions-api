<?php

namespace Tests\Feature\Transaction;

use App\Http\Resources\Transaction\TransactionResource;
use App\Models\Transaction;
use App\Services\Transaction\Contracts\TransactionServiceContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetTransactionTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @var string
     */
    private const ROUTE_NAME = 'transactions.get';

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
    public function test_can_get_existent_transaction(): void
    {
        // Arrange
        $transaction = Transaction::factory()->create();
        $resource = TransactionResource::make($transaction);
        $request = Request::create(route(self::ROUTE_NAME, $transaction->id), 'GET');

        // Act
        $response = $this->get(
            route(self::ROUTE_NAME, $transaction->id)
        );

        // Assert
        $response->assertStatus(Response::HTTP_FOUND);

        $response->assertExactJson([
            'data' => $resource->response($request)->getData(true)['data'],
        ]);
    }

    /**
     * @return void
     */
    public function test_cannot_get_inexistent_transaction(): void
    {
        // Act
        $response = $this->get(
            route(self::ROUTE_NAME, 1550)
        );

        // Assert
        $response->assertStatus(Response::HTTP_NOT_FOUND);

        $response->assertJson([
            'message' => 'Transaction not found',
        ]);
    }
}
