<?php

namespace Database\Seeders;

use App\Enums\StockMovementType;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        $categories = collect([
            'Electronics',
            'Furniture',
            'Stationery',
            'Groceries',
        ])->map(fn (string $name): Category => Category::create([
            'name' => $name,
            'description' => "Products related to {$name}.",
        ]));

        $stockService = app(StockService::class);

        $electronics = $categories->firstWhere('name', 'Electronics');
        $furniture = $categories->firstWhere('name', 'Furniture');
        $stationery = $categories->firstWhere('name', 'Stationery');
        $groceries = $categories->firstWhere('name', 'Groceries');

        $productData = [
            ['category' => $electronics, 'name' => 'Wireless Mouse', 'sku' => 'ELEC-0001', 'unit' => 'pcs', 'price' => 1249.00],
            ['category' => $electronics, 'name' => 'Mechanical Keyboard', 'sku' => 'ELEC-0002', 'unit' => 'pcs', 'price' => 4999.00],
            ['category' => $electronics, 'name' => '27in 4K Monitor', 'sku' => 'ELEC-0003', 'unit' => 'pcs', 'price' => 27999.00],
            ['category' => $furniture, 'name' => 'Office Desk', 'sku' => 'FURN-0001', 'unit' => 'pcs', 'price' => 15999.00],
            ['category' => $furniture, 'name' => 'Ergonomic Chair', 'sku' => 'FURN-0002', 'unit' => 'pcs', 'price' => 12999.00],
            ['category' => $stationery, 'name' => 'A4 Paper Ream', 'sku' => 'STAT-0001', 'unit' => 'ream', 'price' => 499.00],
            ['category' => $stationery, 'name' => 'Gel Pen Pack', 'sku' => 'STAT-0002', 'unit' => 'pack', 'price' => 199.00],
            ['category' => $groceries, 'name' => 'Rice 5kg Bag', 'sku' => 'GROC-0001', 'unit' => 'bag', 'price' => 599.00],
            ['category' => $groceries, 'name' => 'Cooking Oil 1L', 'sku' => 'GROC-0002', 'unit' => 'bottle', 'price' => 145.00],
        ];

        foreach ($productData as $data) {
            $product = Product::create([
                'category_id' => $data['category']->id,
                'name' => $data['name'],
                'sku' => $data['sku'],
                'unit' => $data['unit'],
                'price' => $data['price'],
                'quantity' => 0,
            ]);

            $stockService->recordMovement(
                product: $product,
                type: StockMovementType::In,
                quantity: random_int(15, 80),
                notes: 'Initial stock',
                user: $user,
            );

            if ($product->quantity > 30) {
                $stockService->recordMovement(
                    product: $product,
                    type: StockMovementType::Out,
                    quantity: random_int(3, 12),
                    notes: 'Sample sale',
                    user: $user,
                );
            }
        }
    }
}
