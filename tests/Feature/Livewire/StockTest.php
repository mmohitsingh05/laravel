<?php

declare(strict_types=1);

use App\Livewire\Stock\StockForm;
use App\Models\Category;
use App\Models\Product;
use Livewire\Livewire;

test('stock form page renders for logged-in users', function (): void {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id]);

    $this->actingAs(loggedInUser())
        ->get(route('stock.create', $product))
        ->assertOk()
        ->assertSee('Add stock entry');
});

test('stock can be added to a product', function (): void {
    $this->withoutExceptionHandling();

    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id, 'quantity' => 5]);
    $user = loggedInUser();

    Livewire::actingAs($user)
        ->test(StockForm::class, ['product' => $product])
        ->set('direction', 'in')
        ->set('quantity', 10)
        ->set('notes', 'Restock from supplier')
        ->call('submit')
        ->assertHasNoErrors();

    $product->refresh();
    $this->assertSame(15, $product->quantity);
    $this->assertDatabaseHas('stock_movements', [
        'product_id' => $product->id,
        'type' => 'in',
        'quantity' => 10,
        'user_id' => $user->id,
    ]);
});

test('stock can be removed from a product', function (): void {
    $this->withoutExceptionHandling();

    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id, 'quantity' => 10]);
    $user = loggedInUser();

    Livewire::actingAs($user)
        ->test(StockForm::class, ['product' => $product])
        ->set('direction', 'out')
        ->set('quantity', 4)
        ->set('notes', 'Sold to customer')
        ->call('submit')
        ->assertHasNoErrors();

    $product->refresh();
    $this->assertSame(6, $product->quantity);
    $this->assertDatabaseHas('stock_movements', [
        'product_id' => $product->id,
        'type' => 'out',
        'quantity' => 4,
        'user_id' => $user->id,
    ]);
});

test('stock out exceeding available quantity is rejected', function (): void {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id, 'quantity' => 5]);

    Livewire::actingAs(loggedInUser())
        ->test(StockForm::class, ['product' => $product])
        ->set('direction', 'out')
        ->set('quantity', 10)
        ->call('submit')
        ->assertHasErrors(['quantity']);

    $product->refresh();
    $this->assertSame(5, $product->quantity);
    $this->assertDatabaseCount('stock_movements', 0);
});

test('stock quantity cannot be zero or negative', function (): void {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id, 'quantity' => 5]);

    Livewire::actingAs(loggedInUser())
        ->test(StockForm::class, ['product' => $product])
        ->set('direction', 'in')
        ->set('quantity', 0)
        ->call('submit')
        ->assertHasErrors(['quantity']);
});

test('direction must be in or out', function (): void {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id]);

    Livewire::actingAs(loggedInUser())
        ->test(StockForm::class, ['product' => $product])
        ->set('direction', 'invalid')
        ->call('submit')
        ->assertHasErrors(['direction']);
});

test('stock movement updates the product quantity atomically', function (): void {
    $this->withoutExceptionHandling();

    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id, 'quantity' => 0]);
    $user = loggedInUser();

    Livewire::actingAs($user)
        ->test(StockForm::class, ['product' => $product])
        ->set('direction', 'in')
        ->set('quantity', 1)
        ->call('submit')
        ->assertHasNoErrors();

    $product->refresh();
    $this->assertSame(1, $product->quantity);

    Livewire::actingAs($user)
        ->test(StockForm::class, ['product' => $product])
        ->set('direction', 'out')
        ->set('quantity', 1)
        ->call('submit')
        ->assertHasNoErrors();

    $product->refresh();
    $this->assertSame(0, $product->quantity);
});
