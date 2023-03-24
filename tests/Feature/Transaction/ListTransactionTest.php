<?php

namespace Tests\Feature\Transaction;

use App\Enums\Status;
use App\Http\Resources\Transaction\TransactionResource;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class ListTransactionTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @var string
     */
    private const ROUTE_NAME = 'transactions.list';

    /**
     * @return void
     */
    public function test_can_list_all_transactions_without_filter(): void
    {
        // Arrange
        $transactions = Transaction::factory(10)->create();
        $resource = TransactionResource::collection($transactions);
        $request = Request::create(route(self::ROUTE_NAME), 'GET');

        // Act
        $response = $this->get(
            route(self::ROUTE_NAME)
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
    public function test_can_list_transactions_with_filter_by_payer_id(): void
    {
        // Arrange
        $user = User::factory()->create();
        $transactions = Transaction::factory(10)->create([
            'payer_id' => $user->id,
        ]);
        $resource = TransactionResource::collection(
            Transaction::where('payer_id', $user->id)->get()
        );
        $parameters = [
            'payer_id' => $transactions->first()->payer_id,
        ];
        $request = Request::create(route(self::ROUTE_NAME), 'GET', $parameters);

        // Act
        $response = $this->get(
            route(self::ROUTE_NAME),
            $parameters
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
    public function test_can_list_transactions_with_filter_by_payee_id(): void
    {
        // Arrange
        $user = User::factory()->create();
        $transactions = Transaction::factory(10)->create([
            'payee_id' => $user->id,
        ]);
        $resource = TransactionResource::collection(
            Transaction::where('payee_id', $user->id)->get()
        );
        $parameters = [
            'payee_id' => $transactions->first()->payee_id,
        ];
        $request = Request::create(route(self::ROUTE_NAME), 'GET', $parameters);

        // Act
        $response = $this->get(
            route(self::ROUTE_NAME),
            $parameters
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
    public function test_can_list_transactions_with_filter_by_status_id(): void
    {
        // Arrange
        Transaction::factory(10)->create();
        $resource = TransactionResource::collection(
            Transaction::where('status_id', Status::COMPLETE->value)->get()
        );
        $parameters = [
            'status_id' => Status::COMPLETE->value,
        ];
        $request = Request::create(route(self::ROUTE_NAME), 'GET', $parameters);

        // Act
        $response = $this->get(
            route(self::ROUTE_NAME),
            $parameters
        );

        // Assert
        $response->assertStatus(Response::HTTP_FOUND);

        $response->assertExactJson([
            'data' => $resource->response($request)->getData(true)['data'],
        ]);
    }
}
