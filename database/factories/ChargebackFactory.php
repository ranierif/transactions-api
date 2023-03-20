<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chargeback>
 */
class ChargebackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'origin_transaction_id' => Transaction::factory()->create()->id,
            'reversal_transaction_id' => Transaction::factory()->create()->id,
            'reason' => fake()->realText(200),
        ];
    }
}
