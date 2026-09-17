<?php

declare(strict_types=1);

namespace App\Livewire\Catalogue;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

final class Index extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public ?int $categoryId = null;

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
            ->paginate(9);
    }

    public function categories(): Collection
    {
        return Category::orderBy('name')->get();
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'categoryId');
    }

    #[Layout('layouts.public')]
    #[Title('Catalogue · Inventory')]
    public function render()
    {
        return view('livewire.catalogue.index', [
            'products' => $this->products(),
            'categories' => $this->categories(),
        ]);
    }
}
