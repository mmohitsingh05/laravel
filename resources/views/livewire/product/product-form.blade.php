<div>
    @if ($open)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-950/50 backdrop-blur-sm" wire:click="close"></div>
            <div class="relative max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-3xl border border-slate-200 bg-white p-8 shadow-2xl dark:border-slate-700 dark:bg-slate-900">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="eyebrow">{{ $productId ? 'Edit product' : 'New product' }}</span>
                        <h3 class="mt-3 font-display text-2xl font-bold text-slate-900 dark:text-white">
                            {{ $productId ? 'Update product' : 'Create a product' }}
                        </h3>
                    </div>
                    <button type="button" wire:click="close" class="inline-flex size-9 cursor-pointer items-center justify-center rounded-full text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="save" class="mt-8 space-y-6">
                    <div>
                        <label for="product-name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Name
                        </label>
                        <input
                            id="product-name"
                            type="text"
                            wire:model="name"
                            placeholder="e.g. Wireless Mouse"
                            class="mt-2 w-full rounded-2xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                        />
                        @error('name')
                            <p class="mt-2 text-sm font-medium text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="product-sku" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                SKU
                            </label>
                            <input
                                id="product-sku"
                                type="text"
                                wire:model="sku"
                                placeholder="e.g. WM-1001"
                                class="mt-2 w-full rounded-2xl border-slate-200 bg-white px-4 py-3 font-mono text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                            />
                            @error('sku')
                                <p class="mt-2 text-sm font-medium text-destructive">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="product-category" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Category
                            </label>
                            <select
                                id="product-category"
                                wire:model="categoryId"
                                class="mt-2 w-full cursor-pointer rounded-2xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                            >
                                <option value="">Select a category...</option>
                                @foreach ($this->categories() as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('categoryId')
                                <p class="mt-2 text-sm font-medium text-destructive">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="product-price" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Price
                            </label>
                            <div class="relative mt-2">
                                <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-400">
                                    ₹
                                </span>
                                <input
                                    id="product-price"
                                    type="number"
                                    wire:model="price"
                                    step="0.01"
                                    min="0"
                                    placeholder="0.00"
                                    class="w-full rounded-2xl border-slate-200 bg-white py-3 pl-9 pr-4 font-mono text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                />
                            </div>
                            @error('price')
                                <p class="mt-2 text-sm font-medium text-destructive">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="product-unit" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Unit
                            </label>
                            <input
                                id="product-unit"
                                type="text"
                                wire:model="unit"
                                placeholder="e.g. pcs, box, kg"
                                class="mt-2 w-full rounded-2xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                            />
                            @error('unit')
                                <p class="mt-2 text-sm font-medium text-destructive">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Image <span class="font-normal text-slate-400">(optional)</span>
                        </label>

                        @if ($image || $existingImage)
                            <div class="mt-3 flex items-center gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/60">
                                <img
                                    src="{{ $image ? $image->temporaryUrl() : \Illuminate\Support\Facades\Storage::disk('public')->url($existingImage) }}"
                                    alt="Product preview"
                                    class="size-16 rounded-xl object-cover"
                                />
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                        {{ $image ? 'New image selected' : 'Current image' }}
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">JPG, PNG or WebP · up to 2MB</p>
                                </div>
                                <button
                                    type="button"
                                    wire:click="removeImage"
                                    class="inline-flex cursor-pointer items-center rounded-full px-4 py-2 text-xs font-semibold text-destructive transition-colors hover:bg-red-50 dark:hover:bg-red-950/50"
                                >
                                    Remove
                                </button>
                            </div>
                        @endif

                        <label for="product-image" class="mt-3 flex w-full cursor-pointer flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 px-4 py-8 transition-colors hover:border-primary-400 hover:bg-primary-50/40 dark:border-slate-700 dark:bg-slate-800/40 dark:hover:border-primary-700 dark:hover:bg-primary-950/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                                <circle cx="9" cy="9" r="2" />
                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                            </svg>
                            <span class="text-sm font-semibold text-slate-600 dark:text-slate-300">
                                {{ $image || $existingImage ? 'Choose a different image' : 'Click to upload a product image' }}
                            </span>
                            <span class="text-xs text-slate-400">JPG, PNG or WebP · up to 2MB</span>
                            <input
                                id="product-image"
                                type="file"
                                accept="image/jpeg,image/png,image/webp"
                                wire:model="image"
                                class="sr-only"
                            />
                        </label>
                        @error('image')
                            <p class="mt-2 text-sm font-medium text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="product-description" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Description <span class="font-normal text-slate-400">(optional)</span>
                        </label>
                        <textarea
                            id="product-description"
                            wire:model="description"
                            rows="3"
                            placeholder="A short description of this product..."
                            class="mt-2 w-full rounded-2xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                        ></textarea>
                        @error('description')
                            <p class="mt-2 text-sm font-medium text-destructive">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" wire:click="close" class="btn-secondary cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary cursor-pointer">
                            {{ $productId ? 'Save changes' : 'Create product' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>