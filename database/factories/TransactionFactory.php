<?php

namespace Database\Factories;

use App\Enums\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payer_id' => User::all()->shuffle()->first(),
            'payee_id' => User::all()->shuffle()->first(),
            'status_id' => Status::COMPLETE->value,
            'value' => random_int(1, 100000),
        ];
    }
}
