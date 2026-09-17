<?php

declare(strict_types=1);

use App\Livewire\Dashboard\Index as Dashboard;
use App\Models\Category;
use App\Models\Product;
use Livewire\Livewire;

test('dashboard renders for logged-in users', function (): void {
    $this->actingAs(loggedInUser())
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Inventory dashboard');
});

test('unauthenticated visitors are redirected to login', function (): void {
    $this->get(route('dashboard'))
        ->assertRedirect(route('login'));
});

test('dashboard shows inventory stats', function (): void {
    $category = Category::factory()->create();
    Product::factory()->count(3)->create(['category_id' => $category->id, 'quantity' => 25]);

    $this->actingAs(loggedInUser());

    Livewire::test(Dashboard::class)
        ->assertViewHas('stats', function (array $stats): bool {
            return $stats['products'] === 3
                && $stats['categories'] === 1
                && $stats['totalUnits'] === 75
                && $stats['lowStock'] === 0;
        });
});

test('dashboard highlights low stock products', function (): void {
    $category = Category::factory()->create();
    Product::factory()->create(['category_id' => $category->id, 'quantity' => 4]);

    $this->actingAs(loggedInUser());

    Livewire::test(Dashboard::class)
        ->assertViewHas('stats', fn (array $stats): bool => $stats['lowStock'] === 1)
        ->assertSee('Low stock products');
});
