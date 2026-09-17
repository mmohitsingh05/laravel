<div wire:poll.10s>
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-10 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <span class="eyebrow">Stock</span>
                <h1 class="mt-4 font-display text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-5xl">
                    Stock movements
                </h1>
                <p class="mt-3 max-w-xl text-base text-slate-500 dark:text-slate-400">
                    A live log of every inbound and outbound movement across your catalogue.
                </p>
            </div>
        </div>

        @if (session('message'))
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21.801 10A10 10 0 1 1 17 3.335" />
                    <path d="m9 11 3 3L22 4" />
                </svg>
                {{ session('message') }}
            </div>
        @endif

        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="font-display text-lg font-bold text-slate-900 dark:text-white">All movements</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $movements->total() }} total</p>
            </div>
            <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row sm:items-center">
                <div class="relative w-full sm:w-64">
                    <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                    <input
                        type="search"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search product or SKU..."
                        class="w-full rounded-full border-slate-200 bg-white py-2.5 pl-11 pr-4 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                    />
                </div>
                <select
                    wire:model.live="type"
                    class="w-full cursor-pointer rounded-full border-slate-200 bg-white py-2.5 pl-4 pr-8 text-sm text-slate-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white sm:w-44"
                >
                    <option value="">All types</option>
                    <option value="in">Stock in</option>
                    <option value="out">Stock out</option>
                </select>
                @if ($search !== '' || $type !== '')
                    <button type="button" wire:click="resetFilters" class="inline-flex cursor-pointer items-center justify-center rounded-full px-4 py-2.5 text-xs font-semibold text-slate-500 transition-colors hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
                        Reset
                    </button>
                @endif
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-[1.75rem] border border-slate-200/80 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Product</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Type</th>
                        <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Quantity</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Notes</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">By</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-900">
                    @forelse ($movements as $movement)
                        <tr class="transition-colors duration-300 hover:bg-slate-50 dark:hover:bg-slate-800/60">
                            <td class="px-6 py-4">
                                <a href="{{ route('products.show', $movement->product) }}" wire:navigate class="font-display text-sm font-semibold text-slate-900 transition-colors hover:text-primary-600 dark:text-white dark:hover:text-primary-400">
                                    {{ $movement->product->name }}
                                </a>
                                <p class="mt-0.5 text-xs text-slate-400 dark:text-slate-500">
                                    <span class="font-mono">{{ $movement->product->sku }}</span>
                                    · {{ $movement->product->category->name }}
                                </p>
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
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                {{ $movement->created_at->format('M j, Y g:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                                    {{ $search !== '' || $type !== '' ? 'No movements match your filters.' : 'No stock movements recorded yet.' }}
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="border-t border-slate-200 px-6 py-4 dark:border-slate-700">
                {{ $movements->links() }}
            </div>
        </div>
    </div>
</div>