<?php

namespace Tests\Feature\Chargeback;

use App\Enums\Status;
use App\Models\Chargeback;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Tests\TestCase;

class StoreChargebackTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @var string
     */
    private const ROUTE_NAME = 'chargeback.store';

    /**
     * @return void
     */
    public function test_can_store_chargeback_successfully(): void
    {
        // Arrange
        $transaction = Transaction::factory()->create();
        $payload = [
            'reason' => $this->faker()->text(200),
        ];

        // Act
        $response = $this->postJson(
            route(self::ROUTE_NAME, $transaction->id),
            $payload
        );

        // Assert
        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas(Chargeback::class, [
            'origin_transaction_id' => $transaction->id,
            'reason' => Arr::get($payload, 'reason'),
        ]);

        $this->assertDatabaseHas(Transaction::class, [
            'payer_id' => $transaction->payee_id,
            'payee_id' => $transaction->payer_id,
            'value' => $transaction->value,
            'status_id' => Status::COMPLETE->value,
        ]);

        $this->assertDatabaseHas(Transaction::class, [
            'id' => $transaction->id,
            'status_id' => Status::CHARGEBACK->value,
        ]);
    }

    /**
     * @return void
     */
    public function test_cannot_store_chargeback_when_already_has_chargeback(): void
    {
        // Arrange
        $transaction = Transaction::factory()->create([
            'status_id' => Status::CHARGEBACK->value,
        ]);
        $payload = [
            'reason' => $this->faker()->text(200),
        ];

        // Act
        $response = $this->postJson(
            route(self::ROUTE_NAME, $transaction->id),
            $payload
        );

        // Assert
        $response->assertStatus(Response::HTTP_BAD_REQUEST);

        $response->assertJson([
            'message' => 'The transaction already has chargeback',
        ]);

        $this->assertDatabaseMissing(Chargeback::class, [
            'origin_transaction_id' => $transaction->id,
            'reason' => Arr::get($payload, 'reason'),
        ]);
    }
}
