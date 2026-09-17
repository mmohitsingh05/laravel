<?php

declare(strict_types=1);

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

final class ProductShow extends Component
{
    public Product $product;

    public function mount(Product $product): void
    {
        $this->product = $product->loadMissing(['category', 'stockMovements.user']);
    }

    #[On('stock-recorded')]
    public function refresh(): void
    {
        $this->product = Product::with(['category', 'stockMovements.user'])
            ->findOrFail($this->product->id);
    }

    #[Layout('layouts.app')]
    #[Title('Product · Inventory')]
    public function render()
    {
        return view('livewire.product.product-show', [
            'product' => $this->product,
            'movements' => $this->product->stockMovements
                ->sortByDesc('created_at')
                ->take(20),
        ]);
    }
}
