<div wire:poll.10s="refresh">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <a href="{{ route('products.index') }}" wire:navigate class="mb-8 inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition-colors hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m12 19-7-7 7-7" />
                <path d="M19 12H5" />
            </svg>
            Back to products
        </a>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <span class="eyebrow">Product</span>
                <h1 class="mt-4 font-display text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-5xl">
                    {{ $product->name }}
                </h1>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center rounded-full bg-primary-50 px-4 py-1.5 text-sm font-semibold text-primary-700 dark:bg-primary-900/50 dark:text-primary-300">
                        {{ $product->category->name }}
                    </span>
                    <span class="inline-flex items-center rounded-full bg-slate-100 px-4 py-1.5 font-mono text-sm font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                        {{ $product->sku }}
                    </span>
                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-4 py-1.5 font-mono text-sm font-bold text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400">
                        {{ $product->price_formatted }}
                    </span>
                </div>

                <div class="mt-8 flex flex-col gap-6 sm:flex-row sm:items-start">
                    @if ($product->image_url)
                        <img
                            src="{{ $product->image_url }}"
                            alt="{{ $product->name }}"
                            class="size-40 shrink-0 rounded-3xl border border-slate-200 object-cover shadow-sm dark:border-slate-700"
                        />
                    @else
                        <div class="flex size-40 shrink-0 items-center justify-center rounded-3xl border border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-16 text-slate-300 dark:text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m12 8 6-3-6-3v10" />
                                <path d="m20 7-8 4 8 4" />
                                <path d="m4 11-2 2" />
                                <path d="m4 17-2-2" />
                                <path d="m12 12 2 3" />
                            </svg>
                        </div>
                    @endif

                    @if ($product->description)
                        <p class="max-w-xl flex-1 text-base leading-relaxed text-slate-600 dark:text-slate-300">
                            {{ $product->description }}
                        </p>
                    @endif
                </div>

                <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-900">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Current stock</p>
                        <p class="mt-3 font-mono text-4xl font-bold text-slate-900 dark:text-white">
                            {{ $product->quantity }}
                        </p>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $product->unit }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-900">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total movements</p>
                        <p class="mt-3 font-mono text-4xl font-bold text-slate-900 dark:text-white">
                            {{ $product->stockMovements->count() }}
                        </p>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">in & out combined</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-900">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Created</p>
                        <p class="mt-3 font-mono text-2xl font-bold text-slate-900 dark:text-white">
                            {{ $product->created_at->format('M j, Y') }}
                        </p>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $product->created_at->format('g:i A') }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-900 lg:mt-24">
                <h2 class="font-display text-xl font-bold text-slate-900 dark:text-white">Actions</h2>
                <div class="mt-5 space-y-3">
                    <a href="{{ route('products.edit', $product) }}" wire:navigate class="btn-primary w-full">
                        Edit product
                    </a>
                    <a href="{{ route('stock.create', $product) }}" wire:navigate class="btn-secondary w-full">
                        Add stock entry
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-14">
            <h2 class="font-display text-2xl font-bold text-slate-900 dark:text-white">Stock history</h2>

            <div class="mt-6 overflow-hidden rounded-[1.75rem] border border-slate-200/80 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-800">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Date</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Type</th>
                            <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Quantity</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Notes</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">By</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-900">
                        @forelse ($movements as $movement)
                            <tr class="transition-colors duration-300 hover:bg-slate-50 dark:hover:bg-slate-800/60">
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                    {{ $movement->created_at->format('M j, Y g:i A') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if ($movement->type === \App\Enums\StockMovementType::In)
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400">
                                            Stock in
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-destructive dark:bg-red-950/50">
                                            Stock out
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right">
                                    <span class="font-mono text-sm font-bold {{ $movement->type === \App\Enums\StockMovementType::In ? 'text-emerald-600 dark:text-emerald-400' : 'text-destructive' }}">
                                        {{ $movement->type === \App\Enums\StockMovementType::In ? '+' : '−' }}{{ $movement->quantity }}
                                    </span>
                                </td>
                                <td class="max-w-xs px-6 py-4">
                                    <span class="block truncate text-sm text-slate-500 dark:text-slate-400">{{ $movement->notes ?? '—' }}</span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                                    {{ $movement->user?->name ?? 'System' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                                        No stock movements recorded yet.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>