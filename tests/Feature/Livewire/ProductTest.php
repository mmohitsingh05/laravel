<?php

declare(strict_types=1);

use App\Enums\StockMovementType;
use App\Livewire\Product\ProductForm;
use App\Livewire\Product\ProductList;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

function fakeJpeg(): UploadedFile
{
    return UploadedFile::fake()->createWithContent(
        'image.jpg',
        base64_decode(
            '/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/wAALCAABAAEBAREA/8QAFAABAAAAAAAAAAAAAAAAAAAACf/EABQQAQAAAAAAAAAAAAAAAAAAAAD/2gAIAQEAAD8AVN//2Q==',
            true
        ),
    );
}

test('product index page renders for logged-in users', function (): void {
    $this->actingAs(loggedInUser())
        ->get(route('products.index'))
        ->assertOk()
        ->assertSee('Product catalogue');
});

test('unauthenticated visitors are redirected to login', function (): void {
    $this->get(route('products.index'))
        ->assertRedirect(route('login'));
});

test('products are listed with category info', function (): void {
    $category = Category::factory()->create();
    Product::factory()->count(5)->create(['category_id' => $category->id]);

    $this->actingAs(loggedInUser());

    Livewire::test(ProductList::class)
        ->assertViewHas('products', fn ($products) => $products->total() === 5);
});

test('products can be filtered by search (name or SKU)', function (): void {
    $category = Category::factory()->create();
    Product::factory()->create(['name' => 'Wireless Mouse', 'sku' => 'WM-1001', 'category_id' => $category->id]);
    Product::factory()->create(['name' => 'USB Cable', 'sku' => 'UC-2001', 'category_id' => $category->id]);

    $this->actingAs(loggedInUser());

    Livewire::test(ProductList::class)
        ->set('search', 'Mouse')
        ->assertSee('Wireless Mouse')
        ->assertDontSee('USB Cable')
        ->assertViewHas('products', fn ($products) => $products->total() === 1);
});

test('products can be filtered by category', function (): void {
    $electronics = Category::factory()->create(['name' => 'Electronics']);
    $office = Category::factory()->create(['name' => 'Office']);
    Product::factory()->create(['category_id' => $electronics->id]);
    Product::factory()->create(['category_id' => $office->id]);

    $this->actingAs(loggedInUser());

    Livewire::test(ProductList::class)
        ->set('categoryId', $electronics->id)
        ->assertViewHas('products', fn ($products) => $products->total() === 1);
});

test('a product can be created through the form modal', function (): void {
    $category = Category::factory()->create();

    $this->actingAs(loggedInUser());

    Livewire::test(ProductForm::class)
        ->dispatch('open-product-form')
        ->assertSet('open', true)
        ->set('name', 'Wireless Mouse')
        ->set('sku', 'wm-1001')
        ->set('categoryId', $category->id)
        ->set('price', '1249.00')
        ->set('unit', 'pcs')
        ->call('save')
        ->assertSet('open', false)
        ->assertDispatched('product-saved');

    $this->assertDatabaseHas('products', [
        'name' => 'Wireless Mouse',
        'sku' => 'WM-1001',
        'category_id' => $category->id,
        'quantity' => 0,
        'price' => 1249.00,
    ]);
});

test('a product cannot be created without required fields', function (): void {
    $this->actingAs(loggedInUser());

    Livewire::test(ProductForm::class)
        ->dispatch('open-product-form')
        ->call('save')
        ->assertHasErrors(['name', 'sku', 'categoryId', 'price']);

    $this->assertDatabaseCount('products', 0);
});

test('a product SKU must be unique', function (): void {
    $category = Category::factory()->create();
    Product::factory()->create(['sku' => 'WM-1001']);

    $this->actingAs(loggedInUser());

    Livewire::test(ProductForm::class)
        ->dispatch('open-product-form')
        ->set('name', 'Another Mouse')
        ->set('sku', 'wm-1001')
        ->set('categoryId', $category->id)
        ->set('price', '999.00')
        ->call('save')
        ->assertHasErrors(['sku' => 'unique']);
});

test('an existing product can be edited', function (): void {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['name' => 'Old Name', 'category_id' => $category->id]);

    $this->actingAs(loggedInUser());

    Livewire::test(ProductForm::class)
        ->dispatch('edit-product', id: $product->id)
        ->assertSet('open', true)
        ->assertSet('name', 'Old Name')
        ->set('name', 'New Name')
        ->call('save')
        ->assertSet('open', false);

    $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'New Name']);
});

test('editing keeps current SKU valid under unique rule', function (): void {
    $category = Category::factory()->create();
    $product = Product::factory()->create(['sku' => 'WM-1001', 'category_id' => $category->id]);

    $this->actingAs(loggedInUser());

    Livewire::test(ProductForm::class)
        ->dispatch('edit-product', id: $product->id)
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('products', ['id' => $product->id, 'sku' => 'WM-1001']);
});

test('SKU is stored uppercase', function (): void {
    $category = Category::factory()->create();

    $this->actingAs(loggedInUser());

    Livewire::test(ProductForm::class)
        ->dispatch('open-product-form')
        ->set('name', 'USB Cable')
        ->set('sku', 'uc-2001')
        ->set('categoryId', $category->id)
        ->set('price', '499.00')
        ->call('save');

    $this->assertDatabaseHas('products', ['sku' => 'UC-2001']);
});

test('a product can be deleted', function (): void {
    $product = Product::factory()->create();

    $this->actingAs(loggedInUser());

    Livewire::test(ProductList::class)
        ->call('confirmDelete', $product->id)
        ->assertSet('deletingId', $product->id)
        ->call('delete')
        ->assertSet('deletingId', null);

    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});

test('deleting a product cascades stock movements', function (): void {
    $product = Product::factory()->create();
    StockMovement::factory()->create(['product_id' => $product->id, 'type' => StockMovementType::In, 'quantity' => 5]);

    $this->actingAs(loggedInUser());

    Livewire::test(ProductList::class)
        ->call('confirmDelete', $product->id)
        ->call('delete');

    $this->assertDatabaseMissing('products', ['id' => $product->id]);
    $this->assertDatabaseCount('stock_movements', 0);
});

test('cancelling a delete keeps the product', function (): void {
    $product = Product::factory()->create();

    $this->actingAs(loggedInUser());

    Livewire::test(ProductList::class)
        ->call('confirmDelete', $product->id)
        ->call('cancelDelete')
        ->assertSet('deletingId', null);

    $this->assertDatabaseHas('products', ['id' => $product->id]);
});

test('product show page renders', function (): void {
    $product = Product::factory()->create();

    $this->actingAs(loggedInUser())
        ->get(route('products.show', $product))
        ->assertOk()
        ->assertSee($product->name)
        ->assertSee($product->sku);
});

test('product show page displays stock movements', function (): void {
    $product = Product::factory()->create();

    $this->actingAs(loggedInUser())
        ->get(route('products.show', $product))
        ->assertSee('Stock history');
});

test('a product price cannot be negative', function (): void {
    $category = Category::factory()->create();

    $this->actingAs(loggedInUser());

    Livewire::test(ProductForm::class)
        ->dispatch('open-product-form')
        ->set('name', 'Wireless Mouse')
        ->set('sku', 'wm-1001')
        ->set('categoryId', $category->id)
        ->set('price', '-5')
        ->call('save')
        ->assertHasErrors(['price']);

    $this->assertDatabaseCount('products', 0);
});

test('product list shows price', function (): void {
    $category = Category::factory()->create();
    Product::factory()->create(['name' => 'Priced Product', 'price' => 1249.50, 'category_id' => $category->id]);

    $this->actingAs(loggedInUser())
        ->get(route('products.index'))
        ->assertSee('₹1,249.50');
});

test('product show page shows price', function (): void {
    $product = Product::factory()->create(['price' => 499.00]);

    $this->actingAs(loggedInUser())
        ->get(route('products.show', $product))
        ->assertSee('₹499.00');
});

test('a product image can be uploaded', function (): void {
    Storage::fake('public');

    $category = Category::factory()->create();

    $this->actingAs(loggedInUser());

    Livewire::test(ProductForm::class)
        ->dispatch('open-product-form')
        ->set('name', 'Pictured Mouse')
        ->set('sku', 'pic-1001')
        ->set('categoryId', $category->id)
        ->set('price', '799.00')
        ->set('image', fakeJpeg())
        ->call('save')
        ->assertSet('open', false);

    $product = Product::where('sku', 'PIC-1001')->first();

    expect($product)->not->toBeNull();
    expect($product->image_path)->not->toBeNull();

    Storage::disk('public')->assertExists($product->image_path);
});

test('an uploaded image replaces the existing one on edit', function (): void {
    Storage::fake('public');

    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'name' => 'Swappable',
        'category_id' => $category->id,
        'image_path' => 'images/products/old.jpg',
    ]);

    Storage::disk('public')->put('images/products/old.jpg', 'old-image-content');

    $this->actingAs(loggedInUser());

    Livewire::test(ProductForm::class)
        ->dispatch('edit-product', id: $product->id)
        ->set('image', fakeJpeg())
        ->call('save');

    $product->refresh();

    expect($product->image_path)->toStartWith('images/products/');
    expect($product->image_path)->not->toBe('images/products/old.jpg');

    Storage::disk('public')->assertMissing('images/products/old.jpg');
    Storage::disk('public')->assertExists($product->image_path);
});

test('products are paginated and next page can be visited', function (): void {
    $category = Category::factory()->create();
    Product::factory()->count(10)->create(['category_id' => $category->id]);

    $this->actingAs(loggedInUser());

    Livewire::test(ProductList::class)
        ->assertViewHas('products', fn ($products) => $products->count() === 8 && $products->total() === 10);

    Livewire::test(ProductList::class)
        ->set('paginators.page', 2)
        ->assertViewHas('products', fn ($products) => $products->count() === 2 && $products->total() === 10);
});

test('changing the search resets back to the first page', function (): void {
    $category = Category::factory()->create();
    Product::factory()->count(10)->create(['category_id' => $category->id]);

    $this->actingAs(loggedInUser());

    Livewire::test(ProductList::class)
        ->set('paginators.page', 2)
        ->set('search', 'NothingHere')
        ->assertSet('paginators.page', 1);
});

test('newest products are listed first', function (): void {
    $category = Category::factory()->create();
    $oldest = Product::factory()->create(['name' => 'Oldest', 'category_id' => $category->id]);
    $newest = Product::factory()->create(['name' => 'Newest', 'category_id' => $category->id]);

    $this->actingAs(loggedInUser());

    Livewire::test(ProductList::class)
        ->assertViewHas('products', function ($products) use ($oldest, $newest) {
            $ids = $products->pluck('id')->all();

            return $ids === [$newest->id, $oldest->id];
        });
});
