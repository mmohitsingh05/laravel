<?php

namespace Database\Seeders;

use App\Enums\StockMovementType;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );

        $categories = collect([
            'Electronics' => 'Products related to Electronics.',
            'Furniture' => 'Products related to Furniture.',
            'Stationery' => 'Products related to Stationery.',
            'Groceries' => 'Products related to Groceries.',
        ])->map(fn (string $description, string $name): Category => Category::firstOrCreate(
            ['name' => $name],
            ['description' => $description],
        ));

        $stockService = app(StockService::class);

        $productData = [
            ['category' => 'Electronics', 'name' => 'Wireless Mouse', 'sku' => 'ELEC-0001', 'unit' => 'pcs', 'price' => 1249.00],
            ['category' => 'Electronics', 'name' => 'Mechanical Keyboard', 'sku' => 'ELEC-0002', 'unit' => 'pcs', 'price' => 4999.00],
            ['category' => 'Electronics', 'name' => '27in 4K Monitor', 'sku' => 'ELEC-0003', 'unit' => 'pcs', 'price' => 27999.00],
            ['category' => 'Furniture', 'name' => 'Office Desk', 'sku' => 'FURN-0001', 'unit' => 'pcs', 'price' => 15999.00],
            ['category' => 'Furniture', 'name' => 'Ergonomic Chair', 'sku' => 'FURN-0002', 'unit' => 'pcs', 'price' => 12999.00],
            ['category' => 'Stationery', 'name' => 'A4 Paper Ream', 'sku' => 'STAT-0001', 'unit' => 'ream', 'price' => 499.00],
            ['category' => 'Stationery', 'name' => 'Gel Pen Pack', 'sku' => 'STAT-0002', 'unit' => 'pack', 'price' => 199.00],
            ['category' => 'Groceries', 'name' => 'Rice 5kg Bag', 'sku' => 'GROC-0001', 'unit' => 'bag', 'price' => 599.00],
            ['category' => 'Groceries', 'name' => 'Cooking Oil 1L', 'sku' => 'GROC-0002', 'unit' => 'bottle', 'price' => 145.00],
        ];

        foreach ($productData as $data) {
            $product = Product::firstOrCreate(
                ['sku' => $data['sku']],
                [
                    'category_id' => $categories->firstWhere('name', $data['category'])->id,
                    'name' => $data['name'],
                    'unit' => $data['unit'],
                    'price' => $data['price'],
                    'quantity' => 0,
                ],
            );

            if (! $product->wasRecentlyCreated) {
                continue;
            }

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
