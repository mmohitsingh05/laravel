<?php

declare(strict_types=1);

use App\Enums\StockMovementType;
use App\Livewire\Category\CategoryForm;
use App\Livewire\Category\CategoryList;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Livewire\Livewire;

test('category index page renders for logged-in users', function (): void {
    $this->actingAs(loggedInUser())
        ->get(route('categories.index'))
        ->assertOk()
        ->assertSee('Category catalogue');
});

test('unauthenticated visitors are redirected to login', function (): void {
    $this->get(route('categories.index'))
        ->assertRedirect(route('login'));
});

test('categories are listed with product counts and pagination', function (): void {
    Category::factory()->count(10)->create();

    $this->actingAs(loggedInUser());

    Livewire::test(CategoryList::class)
        ->assertViewHas('categories', fn ($categories) => $categories->total() === 10 && $categories->perPage() === 8)
        ->assertSee('2');
});

test('categories can be filtered by search', function (): void {
    Category::factory()->create(['name' => 'Electronics']);
    Category::factory()->create(['name' => 'Office Supplies']);

    $this->actingAs(loggedInUser());

    Livewire::test(CategoryList::class)
        ->set('search', 'Electronics')
        ->assertSee('Electronics')
        ->assertDontSee('Office Supplies')
        ->assertViewHas('categories', fn ($categories) => $categories->total() === 1);
});

test('a category can be created through the form modal', function (): void {
    $this->actingAs(loggedInUser());

    Livewire::test(CategoryForm::class)
        ->assertSet('open', false)
        ->dispatch('open-category-form')
        ->assertSet('open', true)
        ->set('name', 'Peripherals')
        ->set('description', 'Keyboards, mice and displays')
        ->call('save')
        ->assertSet('open', false)
        ->assertDispatched('category-saved');

    $this->assertDatabaseHas('categories', [
        'name' => 'Peripherals',
        'description' => 'Keyboards, mice and displays',
    ]);
});

test('a category cannot be created with invalid data', function (): void {
    $this->actingAs(loggedInUser());

    Livewire::test(CategoryForm::class)
        ->dispatch('open-category-form')
        ->set('name', 'x')
        ->call('save')
        ->assertHasErrors(['name' => 'min']);

    $this->assertDatabaseCount('categories', 0);
});

test('a category name must be unique', function (): void {
    Category::factory()->create(['name' => 'Electronics']);

    $this->actingAs(loggedInUser());

    Livewire::test(CategoryForm::class)
        ->dispatch('open-category-form')
        ->set('name', 'Electronics')
        ->call('save')
        ->assertHasErrors(['name' => 'unique']);

    $this->assertDatabaseCount('categories', 1);
});

test('an existing category can be edited', function (): void {
    $category = Category::factory()->create(['name' => 'Old Name']);

    $this->actingAs(loggedInUser());

    Livewire::test(CategoryForm::class)
        ->dispatch('edit-category', id: $category->id)
        ->assertSet('open', true)
        ->assertSet('name', 'Old Name')
        ->set('name', 'New Name')
        ->call('save')
        ->assertSet('open', false)
        ->assertDispatched('category-saved');

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'New Name',
    ]);
});

test('editing keeps the current category name valid under the unique rule', function (): void {
    $category = Category::factory()->create(['name' => 'Electronics']);

    $this->actingAs(loggedInUser());

    Livewire::test(CategoryForm::class)
        ->dispatch('edit-category', id: $category->id)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Electronics',
    ]);
});

test('a category can be deleted', function (): void {
    $category = Category::factory()->create(['name' => 'To Be Deleted']);

    $this->actingAs(loggedInUser());

    Livewire::test(CategoryList::class)
        ->call('confirmDelete', $category->id)
        ->assertSet('deletingId', $category->id)
        ->call('delete')
        ->assertSet('deletingId', null)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

test('deleting a category cascades to its products and stock movements', function (): void {
    $this->withoutExceptionHandling();

    $category = Category::factory()->create();
    $product = Product::factory()->create(['category_id' => $category->id]);
    StockMovement::factory()->create(['product_id' => $product->id, 'type' => StockMovementType::In, 'quantity' => 5]);

    $this->actingAs(loggedInUser());

    Livewire::test(CategoryList::class)
        ->call('confirmDelete', $category->id)
        ->call('delete')
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    $this->assertDatabaseMissing('products', ['id' => $product->id]);
    $this->assertDatabaseCount('stock_movements', 0);
});

test('cancelling a delete keeps the category', function (): void {
    $category = Category::factory()->create();

    $this->actingAs(loggedInUser());

    Livewire::test(CategoryList::class)
        ->call('confirmDelete', $category->id)
        ->call('cancelDelete')
        ->assertSet('deletingId', null);

    $this->assertDatabaseHas('categories', ['id' => $category->id]);
});
