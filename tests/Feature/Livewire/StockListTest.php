<?php

declare(strict_types=1);

use App\Enums\StockMovementType;
use App\Livewire\Stock\StockList;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Livewire\Livewire;

test('stock movements index page renders for logged-in users', function (): void {
    $this->actingAs(loggedInUser())
        ->get(route('stock.index'))
        ->assertOk()
        ->assertSee('Stock movements');
});

test('unauthenticated visitors are redirected to login on stock index', function (): void {
    $this->get(route('stock.index'))
        ->assertRedirect(route('login'));
});

test('stock movements are listed with product and user info', function (): void {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['name' => 'Gadget', 'category_id' => $category->id]);
    $user = loggedInUser();
    StockMovement::factory()->create([
        'product_id' => $product->id,
        'type' => StockMovementType::In,
        'quantity' => 5,
        'notes' => 'Restock',
        'user_id' => $user->id,
    ]);

    $this->actingAs($user);

    Livewire::test(StockList::class)
        ->assertSee('Gadget')
        ->assertSee('Stock in')
        ->assertSee('Restock')
        ->assertSee($user->name)
        ->assertViewHas('movements', fn ($movements) => $movements->total() === 1);
});

test('stock movements can be filtered by search (product name or SKU)', function (): void {
    $category = Category::factory()->create();
    $mouse = Product::factory()->create(['name' => 'Wireless Mouse', 'sku' => 'WM-1001', 'category_id' => $category->id]);
    $cable = Product::factory()->create(['name' => 'USB Cable', 'sku' => 'UC-2001', 'category_id' => $category->id]);

    StockMovement::factory()->create(['product_id' => $mouse->id, 'type' => StockMovementType::In, 'quantity' => 3]);
    StockMovement::factory()->create(['product_id' => $cable->id, 'type' => StockMovementType::In, 'quantity' => 2]);

    $this->actingAs(loggedInUser());

    Livewire::test(StockList::class)
        ->set('search', 'Mouse')
        ->assertSee('Wireless Mouse')
        ->assertDontSee('USB Cable')
        ->assertViewHas('movements', fn ($movements) => $movements->total() === 1);
});

test('stock movements can be filtered by type', function (): void {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['name' => 'Inbox Gadget', 'category_id' => $category->id]);

    StockMovement::factory()->create(['product_id' => $product->id, 'type' => StockMovementType::In, 'quantity' => 3]);
    StockMovement::factory()->create(['product_id' => $product->id, 'type' => StockMovementType::Out, 'quantity' => 1]);

    $this->actingAs(loggedInUser());

    Livewire::test(StockList::class)
        ->set('type', 'out')
        ->assertViewHas('movements', fn ($movements) => $movements->total() === 1)
        ->assertSee('Stock out')
        ->assertDontSee('+3');

    Livewire::test(StockList::class)
        ->set('type', 'in')
        ->assertViewHas('movements', fn ($movements) => $movements->total() === 1)
        ->assertSee('+3')
        ->assertDontSee('−1');
});

test('stock index shows a friendly empty state when nothing matches', function (): void {
    $this->actingAs(loggedInUser());

    Livewire::test(StockList::class)
        ->set('type', 'out')
        ->assertSee('No movements match your filters');
});

test('stock movements are paginated and next page can be visited', function (): void {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id]);

    StockMovement::factory()->count(12)->create([
        'product_id' => $product->id,
        'type' => StockMovementType::In,
        'quantity' => 1,
    ]);

    $this->actingAs(loggedInUser());

    Livewire::test(StockList::class)
        ->assertViewHas('movements', fn ($movements) => $movements->count() === 10 && $movements->total() === 12);

    Livewire::test(StockList::class)
        ->set('paginators.page', 2)
        ->assertViewHas('movements', fn ($movements) => $movements->count() === 2 && $movements->total() === 12);
});
