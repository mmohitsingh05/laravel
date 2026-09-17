<?php

declare(strict_types=1);

namespace App\Livewire\Stock;

use App\Models\StockMovement;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

final class StockList extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $type = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedType(): void
    {
        $this->resetPage();
    }

    public function movements(): LengthAwarePaginator
    {
        return StockMovement::query()
            ->with(['product.category', 'user'])
            ->when($this->search !== '', fn ($query) => $query
                ->whereHas('product', fn ($product) => $product
                    ->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('sku', 'like', '%'.$this->search.'%')))
            ->when($this->type !== '', fn ($query) => $query->where('type', $this->type))
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    public function resetFilters(): void
    {
        $this->reset('search', 'type');
    }

    #[Layout('layouts.app')]
    #[Title('Stock movements · Inventory')]
    public function render()
    {
        return view('livewire.stock.stock-list', [
            'movements' => $this->movements(),
        ]);
    }
}
