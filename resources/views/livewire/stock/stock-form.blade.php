<div wire:poll.10s="refreshProduct">
    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <a href="{{ route('products.show', $product) }}" wire:navigate class="mb-8 inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition-colors hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m12 19-7-7 7-7" />
                <path d="M19 12H5" />
            </svg>
            Back to {{ $product->name }}
        </a>

        <span class="eyebrow">Stock entry</span>
        <h1 class="mt-4 font-display text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-5xl">
            Add stock entry
        </h1>
        <p class="mt-3 max-w-xl text-base text-slate-500 dark:text-slate-400">
            Record an inbound or outbound movement for <span class="font-semibold text-slate-900 dark:text-white">{{ $product->name }}</span>.
            Current quantity: <span class="font-mono font-semibold text-slate-900 dark:text-white">{{ $product->quantity }} {{ $product->unit }}</span>.
        </p>

        @if (session('message'))
            <div class="mt-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21.801 10A10 10 0 1 1 17 3.335" />
                    <path d="m9 11 3 3L22 4" />
                </svg>
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit="submit" class="mt-8 rounded-[1.75rem] border border-slate-200/80 bg-white p-8 shadow-sm dark:border-slate-700 dark:bg-slate-900 sm:p-10">
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Direction</label>
                    <div class="mt-3 grid grid-cols-2 gap-3">
                        <button
                            type="button"
                            wire:click="$set('direction', 'in')"
                            class="flex cursor-pointer items-center justify-center gap-2 rounded-2xl border-2 px-4 py-4 text-sm font-bold transition-all duration-300 {{ $direction === 'in' ? 'border-emerald-500 bg-emerald-50 text-emerald-700 dark:border-emerald-500 dark:bg-emerald-950 dark:text-emerald-300' : 'border-slate-200 text-slate-500 hover:border-slate-300 dark:border-slate-700 dark:text-slate-400' }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 6v6a4 4 0 0 1-4 4H6" />
                                <path d="M9 10l-3 3 3 3" />
                                <path d="M21 6v2a4 4 0 0 1-4 4h-2" />
                            </svg>
                            Stock in
                        </button>
                        <button
                            type="button"
                            wire:click="$set('direction', 'out')"
                            class="flex cursor-pointer items-center justify-center gap-2 rounded-2xl border-2 px-4 py-4 text-sm font-bold transition-all duration-300 {{ $direction === 'out' ? 'border-red-500 bg-red-50 text-destructive dark:border-red-500 dark:bg-red-950 dark:text-red-300' : 'border-slate-200 text-slate-500 hover:border-slate-300 dark:border-slate-700 dark:text-slate-400' }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 7v7a4 4 0 0 1-4 4H6" />
                                <path d="m14 5-3 3 3 3" />
                                <path d="M6 19V5" />
                            </svg>
                            Stock out
                        </button>
                    </div>
                    @error('direction')
                        <p class="mt-2 text-sm font-medium text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock-quantity" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Quantity ({{ $product->unit }})
                    </label>
                    <input
                        id="stock-quantity"
                        type="number"
                        wire:model="quantity"
                        min="1"
                        placeholder="e.g. 10"
                        class="mt-2 w-full rounded-2xl border-slate-200 bg-white px-4 py-3 font-mono text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                    />
                    @error('quantity')
                        <p class="mt-2 text-sm font-medium text-destructive">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Available stock</label>
                    <div class="mt-2 flex h-[46px] items-center rounded-2xl border border-slate-200 bg-slate-50 px-4 font-mono text-lg font-bold text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                        {{ $product->quantity }}
                        <span class="ml-2 text-xs font-medium text-slate-400">{{ $product->unit }}</span>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="stock-notes" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Notes <span class="font-normal text-slate-400">(optional)</span>
                    </label>
                    <textarea
                        id="stock-notes"
                        wire:model="notes"
                        rows="3"
                        placeholder="e.g. Restock from supplier, damaged units returned..."
                        class="mt-2 w-full rounded-2xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                    ></textarea>
                    @error('notes')
                        <p class="mt-2 text-sm font-medium text-destructive">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-3">
                <a href="{{ route('products.show', $product) }}" wire:navigate class="btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    Record stock {{ $direction }}
                </button>
            </div>
        </form>
    </div>
</div>