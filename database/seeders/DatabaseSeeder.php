<?php

namespace Database\Seeders;

use App\Enums\StockMovementType;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = collect([
            ['name' => 'Admin', 'email' => 'admin@example.com'],
            ['name' => 'Priya Sharma', 'email' => 'manager@example.com'],
            ['name' => 'Rahul Verma', 'email' => 'staff@example.com'],
        ])->map(fn (array $data): User => User::firstOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        ));

        $categories = collect([
            'Electronics' => 'Phones, computers and accessories.',
            'Furniture' => 'Office and home furniture.',
            'Stationery' => 'Papers, pens and office supplies.',
            'Groceries' => 'Daily use food and household items.',
            'Apparel' => 'Clothing and footwear.',
            'Sports & Outdoors' => 'Fitness and outdoor equipment.',
            'Home & Kitchen' => 'Cookware and home essentials.',
            'Toys & Games' => 'Toys, puzzles and board games.',
        ])->map(fn (string $description, string $name): Category => Category::firstOrCreate(
            ['name' => $name],
            ['description' => $description],
        ));

        $stockService = app(StockService::class);

        foreach ($this->products() as $data) {
            $product = Product::firstOrCreate(
                ['sku' => $data['sku']],
                [
                    'category_id' => $categories->firstWhere('name', $data['category'])->id,
                    'name' => $data['name'],
                    'description' => "Quality {$data['name']} for everyday use.",
                    'unit' => $data['unit'],
                    'price' => $data['price'],
                    'quantity' => 0,
                ],
            );

            if (! $product->wasRecentlyCreated) {
                continue;
            }

            $this->seedMovements($product, $stockService, $users);
        }
    }

    /**
     * @param  Collection<int, User>  $users
     */
    private function seedMovements(Product $product, StockService $stockService, Collection $users): void
    {
        $stockService->recordMovement(
            product: $product,
            type: StockMovementType::In,
            quantity: random_int(25, 90),
            notes: 'Initial stock',
            user: $users->random(),
        );

        for ($i = 0; $i < random_int(1, 4); $i++) {
            $quantity = random_int(1, 15);

            if ($product->quantity - $quantity < 0) {
                break;
            }

            $stockService->recordMovement(
                product: $product,
                type: StockMovementType::Out,
                quantity: $quantity,
                notes: 'Sample sale',
                user: $users->random(),
            );
        }

        if (random_int(0, 1) === 1) {
            $stockService->recordMovement(
                product: $product,
                type: StockMovementType::In,
                quantity: random_int(5, 40),
                notes: 'Restock',
                user: $users->random(),
            );
        }
    }

    /**
     * @return array<int, array{category: string, name: string, sku: string, unit: string, price: float}>
     */
    private function products(): array
    {
        return [
            ['category' => 'Electronics', 'name' => 'Wireless Mouse', 'sku' => 'ELEC-0001', 'unit' => 'pcs', 'price' => 1249.00],
            ['category' => 'Electronics', 'name' => 'Mechanical Keyboard', 'sku' => 'ELEC-0002', 'unit' => 'pcs', 'price' => 4999.00],
            ['category' => 'Electronics', 'name' => '27in 4K Monitor', 'sku' => 'ELEC-0003', 'unit' => 'pcs', 'price' => 27999.00],
            ['category' => 'Electronics', 'name' => 'USB-C Charger 65W', 'sku' => 'ELEC-0004', 'unit' => 'pcs', 'price' => 1899.00],
            ['category' => 'Furniture', 'name' => 'Office Desk', 'sku' => 'FURN-0001', 'unit' => 'pcs', 'price' => 15999.00],
            ['category' => 'Furniture', 'name' => 'Ergonomic Chair', 'sku' => 'FURN-0002', 'unit' => 'pcs', 'price' => 12999.00],
            ['category' => 'Furniture', 'name' => 'Bookshelf 4-Tier', 'sku' => 'FURN-0003', 'unit' => 'pcs', 'price' => 6499.00],
            ['category' => 'Stationery', 'name' => 'A4 Paper Ream', 'sku' => 'STAT-0001', 'unit' => 'ream', 'price' => 499.00],
            ['category' => 'Stationery', 'name' => 'Gel Pen Pack', 'sku' => 'STAT-0002', 'unit' => 'pack', 'price' => 199.00],
            ['category' => 'Stationery', 'name' => 'Sticky Notes Pack', 'sku' => 'STAT-0003', 'unit' => 'pack', 'price' => 149.00],
            ['category' => 'Groceries', 'name' => 'Rice 5kg Bag', 'sku' => 'GROC-0001', 'unit' => 'bag', 'price' => 599.00],
            ['category' => 'Groceries', 'name' => 'Cooking Oil 1L', 'sku' => 'GROC-0002', 'unit' => 'bottle', 'price' => 145.00],
            ['category' => 'Groceries', 'name' => 'Wheat Flour 10kg', 'sku' => 'GROC-0003', 'unit' => 'bag', 'price' => 449.00],
            ['category' => 'Apparel', 'name' => 'Cotton T-Shirt', 'sku' => 'APPA-0001', 'unit' => 'pcs', 'price' => 699.00],
            ['category' => 'Apparel', 'name' => 'Denim Jeans', 'sku' => 'APPA-0002', 'unit' => 'pcs', 'price' => 1899.00],
            ['category' => 'Apparel', 'name' => 'Running Shoes', 'sku' => 'APPA-0003', 'unit' => 'pair', 'price' => 3499.00],
            ['category' => 'Sports & Outdoors', 'name' => 'Yoga Mat', 'sku' => 'SPOR-0001', 'unit' => 'pcs', 'price' => 899.00],
            ['category' => 'Sports & Outdoors', 'name' => 'Dumbbell Set 20kg', 'sku' => 'SPOR-0002', 'unit' => 'set', 'price' => 2999.00],
            ['category' => 'Sports & Outdoors', 'name' => 'Cricket Bat', 'sku' => 'SPOR-0003', 'unit' => 'pcs', 'price' => 2499.00],
            ['category' => 'Home & Kitchen', 'name' => 'Non-Stick Frying Pan', 'sku' => 'HOME-0001', 'unit' => 'pcs', 'price' => 1299.00],
            ['category' => 'Home & Kitchen', 'name' => 'Cookware Set', 'sku' => 'HOME-0002', 'unit' => 'set', 'price' => 4999.00],
            ['category' => 'Home & Kitchen', 'name' => 'Table Lamp', 'sku' => 'HOME-0003', 'unit' => 'pcs', 'price' => 799.00],
            ['category' => 'Toys & Games', 'name' => 'Building Blocks Set', 'sku' => 'TOYS-0001', 'unit' => 'set', 'price' => 1099.00],
            ['category' => 'Toys & Games', 'name' => 'Classic Board Game', 'sku' => 'TOYS-0002', 'unit' => 'pcs', 'price' => 899.00],
            ['category' => 'Toys & Games', 'name' => 'Remote Control Car', 'sku' => 'TOYS-0003', 'unit' => 'pcs', 'price' => 1599.00],
        ];
    }
}
