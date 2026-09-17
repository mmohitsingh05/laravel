<?php

declare(strict_types=1);

namespace App\Livewire\Stock;

use App\Enums\StockMovementType;
use App\Models\Product;
use App\Services\StockService;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

final class StockForm extends Component
{
    public Product $product;

    public string $direction = 'in';

    public int|string $quantity = '';

    public ?string $notes = null;

    public function mount(Product $product): void
    {
        $this->product = $product;
    }

    public function refreshProduct(): void
    {
        $this->product = $this->product->fresh();
    }

    protected function rules(): array
    {
        return [
            'direction' => ['required', 'in:in,out'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function submit(): void
    {
        $this->validate();

        $type = StockMovementType::from($this->direction);

        try {
            app(StockService::class)->recordMovement(
                product: $this->product,
                type: $type,
                quantity: (int) $this->quantity,
                notes: $this->notes,
            );
        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());

            return;
        }

        $this->product = $this->product->fresh();

        $recorded = (int) $this->quantity;

        $this->reset('quantity', 'notes');

        session()->flash('message', 'Stock '.$this->direction.' of '.$recorded.' '.$this->product->unit.' recorded. New quantity: '.$this->product->quantity.'.');

        $this->dispatch('stock-recorded');
    }

    #[Layout('layouts.app')]
    #[Title('Stock entry · Inventory')]
    public function render()
    {
        return view('livewire.stock.stock-form');
    }
}
