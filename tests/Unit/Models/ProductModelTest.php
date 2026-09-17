<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;

test('category has many products', function (): void {
    $category = Category::factory()->create();
    $products = Product::factory()->count(3)->create(['category_id' => $category->id]);

    expect($category->products)->toHaveCount(3)
        ->and($products->first()->category->is($category))->toBeTrue();
});

test('deleting a category cascades to its products', function (): void {
    $category = Category::factory()->create();
    Product::factory()->count(3)->create(['category_id' => $category->id]);

    $category->delete();

    expect(Product::query()->count())->toBe(0);
});

test('product quantity is cast to integer', function (): void {
    $product = Product::factory()->withStock(15)->create();

    expect($product->quantity)->toBeInt()
        ->and($product->quantity)->toBe(15);
});

test('product with stock below threshold is reported by scope', function (): void {
    Product::factory()->withStock(5)->create();
    Product::factory()->withStock(50)->create();

    expect(Product::query()->withStockBelow(10)->count())->toBe(1);
});
