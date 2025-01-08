<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Commission;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commission>
 */
class CommissionFactory extends Factory
{

    protected $model = \App\Models\Commission::class;

    public function definition()
    {
        return [
            'transaction_id' => $this->faker->numberBetween(1, 100),
            'amount' => $this->faker->randomFloat(2, 1, 100),
            'created_at' => $this->faker->dateTimeThisYear(),
            'updated_at' => now(),
        ];
    }
}
