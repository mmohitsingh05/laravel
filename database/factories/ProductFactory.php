<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => ucfirst(rtrim($this->faker->sentence(3), '.')),
            'sku' => strtoupper($this->faker->unique()->regexify('[A-Z]{4}-[0-9]{4}')),
            'description' => $this->faker->optional()->sentence(),
            'quantity' => 0,
            'unit' => 'pcs',
            'price' => $this->faker->randomFloat(2, 50, 5000),
            'image_path' => null,
        ];
    }

    public function withStock(int $quantity): static
    {
        return $this->state(fn (): array => ['quantity' => max(0, $quantity)]);
    }
}
