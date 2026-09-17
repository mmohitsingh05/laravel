<div>
    @if ($open)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-950/50 backdrop-blur-sm" wire:click="close"></div>
            <div class="relative w-full max-w-lg rounded-3xl border border-slate-200 bg-white p-8 shadow-2xl dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="eyebrow">{{ $categoryId ? 'Edit category' : 'New category' }}</span>
                        <h3 class="mt-3 font-display text-2xl font-bold text-slate-900 dark:text-white">
                            {{ $categoryId ? 'Update category' : 'Create a category' }}
                        </h3>
                    </div>
                    <button type="button" wire:click="close" class="inline-flex size-9 items-center justify-center rounded-full text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="save" class="mt-8 space-y-6">
                    <div>
                        <label for="category-name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Name
                        </label>
                        <input
                            id="category-name"
                            type="text"
                            wire:model="name"
                            placeholder="e.g. Electronics"
                            class="mt-2 w-full rounded-2xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                        />
                        @error('name')
                            <p class="mt-2 text-sm font-medium text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category-description" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Description <span class="font-normal text-slate-400">(optional)</span>
                        </label>
                        <textarea
                            id="category-description"
                            wire:model="description"
                            rows="3"
                            placeholder="A short description of this category..."
                            class="mt-2 w-full rounded-2xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                        ></textarea>
                        @error('description')
                            <p class="mt-2 text-sm font-medium text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" wire:click="close" class="btn-secondary">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary">
                            {{ $categoryId ? 'Save changes' : 'Create category' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>