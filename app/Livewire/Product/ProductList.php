<?php

declare(strict_types=1);

namespace App\Livewire\Product;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

final class ProductList extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public ?int $categoryId = null;

    public ?int $deletingId = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryId(): void
    {
        $this->resetPage();
    }

    public function products(): LengthAwarePaginator
    {
        return Product::query()
            ->with('category')
            ->when($this->search !== '', fn ($query) => $query
                ->where('name', 'like', '%'.$this->search.'%')
                ->orWhere('sku', 'like', '%'.$this->search.'%'))
            ->when($this->categoryId, fn ($query) => $query->where('category_id', $this->categoryId))
            ->latest('id')
            ->paginate(8);
    }

    public function categories(): Collection
    {
        return Category::orderBy('name')->get();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
    }

    public function delete(): void
    {
        $product = Product::findOrFail($this->deletingId);

        $name = $product->name;

        $product->delete();

        $this->deletingId = null;

        session()->flash('message', 'Product "'.$name.'" deleted.');
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'categoryId');
    }

    #[Layout('layouts.app')]
    #[Title('Products · Inventory')]
    public function render()
    {
        return view('livewire.product.product-list', [
            'products' => $this->products(),
            'categories' => $this->categories(),
        ]);
    }
}
