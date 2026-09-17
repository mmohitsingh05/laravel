<?php

declare(strict_types=1);

namespace App\Livewire\Product;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

final class ProductForm extends Component
{
    use WithFileUploads;

    public bool $open = false;

    public ?int $productId = null;

    public ?int $categoryId = null;

    public string $name = '';

    public string $sku = '';

    public ?string $description = null;

    public string $unit = 'pcs';

    public int|float|string $price = '';

    public $image = null;

    public ?string $existingImage = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'sku' => ['required', 'string', 'min:2', 'max:64', Rule::unique('products', 'sku')->ignore($this->productId)],
            'categoryId' => ['required', 'integer', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:1000'],
            'unit' => ['required', 'string', 'max:32'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function categories(): Collection
    {
        return Category::orderBy('name')->get();
    }

    #[On('open-product-form')]
    public function createMode(): void
    {
        $this->reset('productId', 'categoryId', 'name', 'sku', 'description', 'unit', 'open');
        $this->unit = 'pcs';
        $this->price = '';
        $this->image = null;
        $this->existingImage = null;
        $this->resetValidation();
        $this->open = true;
    }

    #[On('edit-product')]
    public function editMode(int $id): void
    {
        $product = Product::findOrFail($id);

        $this->productId = $product->id;
        $this->categoryId = $product->category_id;
        $this->name = $product->name;
        $this->sku = $product->sku;
        $this->description = $product->description;
        $this->unit = $product->unit;
        $this->price = (string) $product->price;
        $this->image = null;
        $this->existingImage = $product->image_path;
        $this->resetValidation();
        $this->open = true;
    }

    public function removeImage(): void
    {
        if ($this->image) {
            $this->image = null;
        } else {
            $this->existingImage = null;
        }
    }

    public function save(): void
    {
        $this->sku = strtoupper($this->sku);

        $this->validate();

        $payload = [
            'category_id' => $this->categoryId,
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description !== '' && $this->description !== null ? $this->description : null,
            'unit' => $this->unit,
            'price' => (float) $this->price,
        ];

        if ($this->image) {
            $payload['image_path'] = $this->image->store('images/products', 'public');
        }

        if ($this->productId) {
            $product = Product::findOrFail($this->productId);

            if ($this->image && $product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            } elseif ($this->existingImage === null && $product->image_path) {
                Storage::disk('public')->delete($product->image_path);
                $payload['image_path'] = null;
            }

            $product->update($payload);
            $message = 'Product "'.$product->name.'" updated.';
        } else {
            $product = Product::create($payload);
            $message = 'Product "'.$product->name.'" created.';
        }

        $this->reset('productId', 'categoryId', 'name', 'sku', 'description', 'unit', 'open');
        $this->price = '';
        $this->image = null;
        $this->existingImage = null;
        $this->resetValidation();

        $this->dispatch('product-saved');

        session()->flash('message', $message);
    }

    public function close(): void
    {
        $this->open = false;
    }
}
