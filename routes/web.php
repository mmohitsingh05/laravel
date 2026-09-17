<?php

use App\Livewire\Catalogue\Index as Catalogue;
use App\Livewire\Category\CategoryForm;
use App\Livewire\Category\CategoryList;
use App\Livewire\Dashboard\Index as Dashboard;
use App\Livewire\Product\ProductForm;
use App\Livewire\Product\ProductList;
use App\Livewire\Product\ProductShow;
use App\Livewire\Stock\StockForm;
use App\Livewire\Stock\StockList;
use Illuminate\Support\Facades\Route;
use Livewire\Mechanisms\HandleRequests\EndpointResolver;

Route::view('/', 'welcome');

Route::get('catalogue', Catalogue::class)->name('catalogue.index');

// Livewire's update endpoint is POST-only. A stray browser navigation or refresh
// to that URL would otherwise throw a 405 MethodNotAllowedHttpException. Redirect it.
Route::get(EndpointResolver::updatePath(), function () {
    return redirect(url()->previous(route('catalogue.index')));
})->name('livewire.update.get');

Route::get('dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('categories', CategoryList::class)->name('categories.index');
    Route::get('categories/{category}/edit', CategoryForm::class)->name('categories.edit');

    Route::get('products', ProductList::class)->name('products.index');
    Route::get('products/{product}', ProductShow::class)->name('products.show');
    Route::get('products/{product}/edit', ProductForm::class)->name('products.edit');

    Route::get('products/{product}/stock', StockForm::class)->name('stock.create');

    Route::get('stock', StockList::class)->name('stock.index');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
