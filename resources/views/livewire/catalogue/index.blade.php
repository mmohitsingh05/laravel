<div x-data="{ open: false }" class="relative overflow-hidden">
    <div class="pointer-events-none absolute -top-40 right-0 size-[400px] rounded-full bg-primary-200/40 blur-3xl dark:bg-primary-900/20"></div>
    <div class="pointer-events-none absolute left-0 top-1/3 size-[300px] rounded-full bg-emerald-200/30 blur-3xl dark:bg-emerald-900/10"></div>

    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <span class="eyebrow">Catalogue</span>
            <h1 class="mt-4 font-display text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-5xl">
                Every product we stock
            </h1>
            <p class="mt-3 max-w-xl text-base text-slate-500 dark:text-slate-400">
                Browse the full inventory — search by name or SKU and narrow by category.
            </p>
        </div>

        <div class="mt-10 flex items-center justify-between">
            <p class="text-sm text-slate-500 dark:text-slate-400">
                <span class="font-semibold text-slate-900 dark:text-white">{{ $products->total() }}</span> products
            </p>
            <button
                type="button"
                @click="open = true"
                class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-colors duration-300 hover:border-slate-300 hover:bg-slate-50 lg:hidden dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 3H2l8 9.46V19l4 2v-8.54Z" />
                </svg>
                Filters
            </button>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-8 lg:grid-cols-[16rem_1fr]">
            <div class="hidden lg:block">
                <div class="sticky top-24 rounded-[1.75rem] border border-slate-200/80 bg-white p-1.5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="rounded-[calc(1.75rem-0.375rem)] bg-white/60 p-6 dark:bg-slate-900/60">
                        <div class="flex items-center justify-between">
                            <span class="eyebrow">Filters</span>
                            @if ($search !== '' || $categoryId !== null)
                                <button type="button" wire:click="resetFilters" class="cursor-pointer text-xs font-semibold text-primary-600 transition-colors hover:text-primary-700 dark:text-primary-400">
                                    Clear all
                                </button>
                            @endif
                        </div>

                        <div class="relative mt-5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                            <input
                                type="search"
                                wire:model.live.debounce.300ms="search"
                                placeholder="Search name or SKU..."
                                class="w-full rounded-full border-slate-200 bg-white py-2.5 pl-11 pr-4 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                            />
                        </div>

                        <p class="mt-6 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Category</p>
                        <div class="mt-3 space-y-1">
                            <button
                                type="button"
                                wire:click="$set('categoryId', null)"
                                class="flex w-full cursor-pointer items-center justify-between gap-3 rounded-xl px-3 py-2 text-sm font-medium transition-colors duration-300 {{ $categoryId === null ? 'bg-primary-50 text-primary-700 dark:bg-primary-950/60 dark:text-primary-300' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }}"
                            >
                                All categories
                            </button>
                            @foreach ($categories as $category)
                                <button
                                    type="button"
                                    wire:click="$set('categoryId', {{ $category->id }})"
                                    class="flex w-full cursor-pointer items-center justify-between gap-3 rounded-xl px-3 py-2 text-sm font-medium transition-colors duration-300 {{ $categoryId === $category->id ? 'bg-primary-50 text-primary-700 dark:bg-primary-950/60 dark:text-primary-300' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }}"
                                >
                                    <span class="truncate">{{ $category->name }}</span>
                                    <span class="shrink-0 rounded-full bg-slate-100 px-2 py-0.5 font-mono text-[10px] font-semibold text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                                        {{ $category->products_count ?? $category->products()->count() }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div>
                @if ($products->isEmpty())
                    <div class="rounded-[2rem] border border-slate-200/80 bg-white px-8 py-20 text-center shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-7 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </div>
                        <h2 class="mt-5 font-display text-xl font-bold text-slate-900 dark:text-white">No products found</h2>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Try a different search term or category.</p>
                        <button type="button" wire:click="resetFilters" class="btn-primary mt-6">
                            Clear filters
                        </button>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($products as $product)
                            <div class="card-shell">
                                <div class="card-core">
                                    <div class="flex aspect-square w-full items-center justify-center overflow-hidden rounded-[calc(1.75rem-0.75rem)] bg-slate-50 dark:bg-slate-800/60">
                                        @if ($product->image_url)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="size-full object-cover" loading="lazy" />
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-14 text-slate-300 dark:text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="m12 8 6-3-6-3v10" />
                                                <path d="m20 7-8 4 8 4" />
                                                <path d="m4 11-2 2" />
                                                <path d="m4 17-2-2" />
                                                <path d="m12 12 2 3" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="mt-4 flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <h3 class="truncate font-display text-lg font-bold text-slate-900 dark:text-white">{{ $product->name }}</h3>
                                            <p class="mt-1 font-mono text-xs text-slate-400 dark:text-slate-500">{{ $product->sku }}</p>
                                        </div>
                                        <span class="inline-flex shrink-0 items-center rounded-full bg-primary-50 px-3 py-1 text-xs font-medium text-primary-700 dark:bg-primary-900/50 dark:text-primary-300">
                                            {{ $product->category->name }}
                                        </span>
                                    </div>
                                    <div class="mt-4 flex items-center justify-between gap-3">
                                        <span class="font-mono text-base font-bold text-slate-900 dark:text-white">
                                            {{ $product->price_formatted }}
                                        </span>
                                        @if ($product->quantity <= 0)
                                            <span class="inline-flex items-center rounded-full bg-red-50 px-3 py-1 font-mono text-xs font-semibold text-destructive dark:bg-red-950/50">
                                                Out of stock
                                            </span>
                                        @elseif ($product->quantity <= 10)
                                            <span class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1 font-mono text-xs font-semibold text-amber-700 dark:bg-amber-950/50 dark:text-amber-400">
                                                {{ $product->quantity }} {{ $product->unit }} left
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 font-mono text-xs font-semibold text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400">
                                                {{ $product->quantity }} {{ $product->unit }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-10">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div
        @keydown.escape.window="open = false"
        x-show="open"
        x-cloak
    >
        <div x-show="open" x-cloak x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false" class="fixed inset-0 z-40 bg-slate-950/50 backdrop-blur-sm lg:hidden"></div>

        <div
            x-show="open"
            x-cloak
            x-transition:enter="transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)]"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition-transform duration-300 ease-in"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed inset-y-0 right-0 z-50 w-80 max-w-[85vw] overflow-y-auto rounded-l-[2rem] border border-slate-200/80 bg-white p-6 shadow-2xl lg:hidden dark:border-slate-800 dark:bg-slate-900"
        >
            <div class="flex items-center justify-between">
                <span class="eyebrow">Filters</span>
                <button type="button" @click="open = false" class="inline-flex size-9 items-center justify-center rounded-xl text-slate-400 transition-colors duration-300 hover:bg-slate-100 hover:text-slate-600 lg:hidden dark:hover:bg-slate-800" aria-label="Close filters">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>

            <div class="relative mt-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
                <input
                    type="search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search name or SKU..."
                    class="w-full rounded-full border-slate-200 bg-white py-2.5 pl-11 pr-4 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                />
            </div>

            <p class="mt-6 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Category</p>
            <div class="mt-3 space-y-1">
                <button
                    type="button"
                    @click="open = false"
                    wire:click="$set('categoryId', null)"
                    class="flex w-full cursor-pointer items-center justify-between gap-3 rounded-xl px-3 py-2 text-sm font-medium transition-colors duration-300 {{ $categoryId === null ? 'bg-primary-50 text-primary-700 dark:bg-primary-950/60 dark:text-primary-300' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }}"
                >
                    All categories
                </button>
                @foreach ($categories as $category)
                    <button
                        type="button"
                        @click="open = false"
                        wire:click="$set('categoryId', {{ $category->id }})"
                        class="flex w-full cursor-pointer items-center justify-between gap-3 rounded-xl px-3 py-2 text-sm font-medium transition-colors duration-300 {{ $categoryId === $category->id ? 'bg-primary-50 text-primary-700 dark:bg-primary-950/60 dark:text-primary-300' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }}"
                    >
                        <span class="truncate">{{ $category->name }}</span>
                        <span class="shrink-0 rounded-full bg-slate-100 px-2 py-0.5 font-mono text-[10px] font-semibold text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                            {{ $category->products()->count() }}
                        </span>
                    </button>
                @endforeach
            </div>

            <div class="mt-8 flex items-center justify-between">
                @if ($search !== '' || $categoryId !== null)
                    <button type="button" wire:click="resetFilters" class="text-sm font-semibold text-primary-600 transition-colors hover:text-primary-700 dark:text-primary-400">
                        Clear all
                    </button>
                @endif
                <button type="button" @click="open = false" class="btn-primary !px-5 !py-2.5">
                    Done
                </button>
            </div>
        </div>
    </div>
</div>