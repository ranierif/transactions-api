<?php

namespace Tests\Unit\Services\Transaction;

use App\Enums\DocumentType;
use App\Exceptions\Transaction\InsufficientFundsToSendException;
use App\Exceptions\Transaction\PayerCannotSendTransactionsException;
use App\Models\User;
use App\Services\Transaction\Contracts\ValidatePayerServiceContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidatePayerServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var ValidatePayerServiceContract
     */
    private $validatePayer;

    public function setUp(): void
    {
        parent::setUp();

        $this->validatePayer = app(ValidatePayerServiceContract::class);
    }

    /**
     * @return void
     */
    public function test_check_if_payer_can_send_transaction(): void
    {
        // Arrange
        $payer = User::factory()->create([
            'document_type_id' => DocumentType::CPF->value,
        ]);

        // Act
        $canSendTransaction = $this->validatePayer
            ->canSendTransaction(
                $payer->id,
            );

        // Assert
        $this->assertEmpty($canSendTransaction);
    }

    /**
     * @return void
     */
    public function test_check_if_payer_cannot_send_transaction(): void
    {
        // Arrange
        $this->expectException(PayerCannotSendTransactionsException::class);
        $payer = User::factory()->create([
            'document_type_id' => DocumentType::CNPJ->value,
        ]);

        // Act
        $this->validatePayer
            ->canSendTransaction(
                $payer->id,
            );
    }

    /**
     * @return void
     */
    public function test_check_if_payer_has_balance_to_send_transaction(): void
    {
        // Arrange
        $payer = User::factory()->create([
            'document_type_id' => DocumentType::CPF->value,
        ]);
        $value = $payer->balance;

        // Act
        $hasBalanceToSend = $this->validatePayer
            ->hasBalanceToSend(
                $payer->id,
                $value,
            );

        // Assert
        $this->assertEmpty($hasBalanceToSend);
    }

    /**
     * @return void
     */
    public function test_check_if_payer_not_have_balance_to_send_transaction(): void
    {
        // Arrange
        $this->expectException(InsufficientFundsToSendException::class);
        $payer = User::factory()->create([
            'document_type_id' => DocumentType::CNPJ->value,
        ]);
        $value = $payer->balance + 100;

        // Act
        $this->validatePayer
            ->hasBalanceToSend(
                $payer->id,
                $value,
            );
    }
}
