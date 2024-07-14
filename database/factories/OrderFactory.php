<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'user_id' => $this->faker->randomNumber(),
            'products' => $this->faker->words(),
            'total_price' => $this->faker->randomFloat(),
        ];
    }
}
