<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\StockMovementType;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockMovement>
 */
class StockMovementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'type' => StockMovementType::In,
            'quantity' => $this->faker->numberBetween(1, 100),
            'notes' => $this->faker->optional()->sentence(),
            'user_id' => User::factory(),
        ];
    }

    public function in(): static
    {
        return $this->state(fn (): array => ['type' => StockMovementType::In]);
    }

    public function out(): static
    {
        return $this->state(fn (): array => ['type' => StockMovementType::Out]);
    }
}
