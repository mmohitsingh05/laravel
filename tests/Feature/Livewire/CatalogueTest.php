<?php

declare(strict_types=1);

use App\Livewire\Catalogue\Index as CatalogueIndex;
use App\Models\Category;
use App\Models\Product;
use Livewire\Livewire;
use Livewire\Mechanisms\HandleRequests\EndpointResolver;

test('a GET to the livewire update endpoint redirects instead of 405', function (): void {
    $this->from('/stock')
        ->get(EndpointResolver::updatePath())
        ->assertRedirect('/stock');
});

test('a direct GET to the livewire update endpoint falls back to catalogue', function (): void {
    $this->get(EndpointResolver::updatePath())
        ->assertRedirect();
});

test('catalogue page is publicly accessible', function (): void {
    $this->get(route('catalogue.index'))
        ->assertOk()
        ->assertSee('Every product we stock');
});

test('catalogue page displays products', function (): void {
    $category = Category::factory()->create();
    Product::factory()->count(3)->create(['category_id' => $category->id]);

    Livewire::test(CatalogueIndex::class)
        ->assertOk()
        ->assertViewHas('products', fn ($products) => $products->total() === 3);
});

test('catalogue search filters by product name', function (): void {
    $category = Category::factory()->create();
    Product::factory()->create(['name' => 'Standing Desk', 'sku' => 'SD-001', 'category_id' => $category->id]);
    Product::factory()->create(['name' => 'Ergonomic Chair', 'sku' => 'EC-002', 'category_id' => $category->id]);

    Livewire::test(CatalogueIndex::class)
        ->set('search', 'Standing')
        ->assertViewHas('products', fn ($products) => $products->total() === 1)
        ->assertSee('Standing Desk')
        ->assertDontSee('Ergonomic Chair');
});

test('catalogue category filter works', function (): void {
    $electronics = Category::factory()->create(['name' => 'Electronics']);
    $furniture = Category::factory()->create(['name' => 'Furniture']);
    Product::factory()->create(['category_id' => $electronics->id, 'name' => 'Keyboard']);
    Product::factory()->create(['category_id' => $furniture->id, 'name' => 'Desk']);

    Livewire::test(CatalogueIndex::class)
        ->set('categoryId', $electronics->id)
        ->assertViewHas('products', fn ($products) => $products->total() === 1)
        ->assertSee('Keyboard')
        ->assertDontSee('Desk');
});

test('catalogue resetFilters clears search and categoryId', function (): void {
    $category = Category::factory()->create();
    Product::factory()->create(['name' => 'Widget', 'category_id' => $category->id]);
    Product::factory()->create(['name' => 'Gadget', 'category_id' => $category->id]);

    Livewire::test(CatalogueIndex::class)
        ->set('search', 'Widget')
        ->set('categoryId', $category->id)
        ->call('resetFilters')
        ->assertSet('search', '')
        ->assertSet('categoryId', null)
        ->assertViewHas('products', fn ($products) => $products->total() === 2);
});

test('catalogue renders with 10 products to trigger pagination', function (): void {
    $category = Category::factory()->create();
    Product::factory()->count(10)->create(['category_id' => $category->id]);

    Livewire::test(CatalogueIndex::class)
        ->assertOk()
        ->assertViewHas('products', fn ($products) => $products->total() === 10);
});

test('newest products appear first on catalogue page', function (): void {
    $category = Category::factory()->create();
    $older = Product::factory()->create(['name' => 'Older Product', 'category_id' => $category->id, 'created_at' => now()->subDays(2)]);
    $newer = Product::factory()->create(['name' => 'Newer Product', 'category_id' => $category->id, 'created_at' => now()]);

    Livewire::test(CatalogueIndex::class)
        ->assertViewHas('products', function ($products) use ($newer, $older) {
            $items = $products->getCollection();

            return $items->first()->id === $newer->id && $items->last()->id === $older->id;
        });
});
