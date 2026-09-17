<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

final class Index extends Component
{
    #[On('stock-recorded')]
    #[On('product-saved')]
    #[On('category-saved')]
    public function refresh(): void {}

    public function stats(): array
    {
        return [
            'products' => Product::count(),
            'categories' => Category::count(),
            'totalUnits' => Product::sum('quantity'),
            'lowStock' => Product::withStockBelow(10)->count(),
            'inventoryValue' => (float) Product::query()->sum(DB::raw('price * quantity')),
        ];
    }

    public function movements(): LengthAwarePaginator
    {
        return StockMovement::query()
            ->with(['product.category', 'user'])
            ->orderByDesc('created_at')
            ->paginate(6);
    }

    #[Layout('layouts.app')]
    #[Title('Dashboard · Inventory')]
    public function render()
    {
        return view('livewire.dashboard.index', [
            'stats' => $this->stats(),
            'movements' => $this->movements(),
        ]);
    }
}
