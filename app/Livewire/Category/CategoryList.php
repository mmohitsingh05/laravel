<?php

declare(strict_types=1);

namespace App\Livewire\Category;

use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

final class CategoryList extends Component
{
    #[Url(history: true)]
    public string $search = '';

    public ?int $deletingId = null;

    public function categories(): LengthAwarePaginator
    {
        return Category::query()
            ->withCount('products')
            ->when($this->search !== '', fn ($query) => $query->where('name', 'like', '%'.$this->search.'%'))
            ->orderBy('name')
            ->paginate(8);
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
        $category = Category::findOrFail($this->deletingId);

        $category->delete();

        $this->deletingId = null;

        session()->flash('message', 'Category "'.$category->name.'" deleted.');
    }

    #[Layout('layouts.app')]
    #[Title('Categories · Inventory')]
    public function render()
    {
        return view('livewire.category.category-list', [
            'categories' => $this->categories(),
        ]);
    }
}
