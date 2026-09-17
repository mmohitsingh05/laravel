<?php

declare(strict_types=1);

namespace App\Livewire\Category;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

final class CategoryForm extends Component
{
    public bool $open = false;

    public ?int $categoryId = null;

    public string $name = '';

    public ?string $description = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255', Rule::unique('categories', 'name')->ignore($this->categoryId)],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    #[On('open-category-form')]
    public function createMode(): void
    {
        $this->reset('categoryId', 'name', 'description', 'open');
        $this->resetValidation();
        $this->open = true;
    }

    #[On('edit-category')]
    public function editMode(int $id): void
    {
        $category = Category::findOrFail($id);

        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->resetValidation();
        $this->open = true;
    }

    public function save(): void
    {
        $this->validate();

        $payload = [
            'name' => $this->name,
            'description' => $this->description !== '' && $this->description !== null ? $this->description : null,
        ];

        if ($this->categoryId) {
            $category = Category::findOrFail($this->categoryId);
            $category->update($payload);
            $message = 'Category "'.$category->name.'" updated.';
        } else {
            $category = Category::create($payload);
            $message = 'Category "'.$category->name.'" created.';
        }

        $this->reset('categoryId', 'name', 'description', 'open');
        $this->resetValidation();

        $this->dispatch('category-saved');

        session()->flash('message', $message);
    }

    public function close(): void
    {
        $this->open = false;
    }
}
