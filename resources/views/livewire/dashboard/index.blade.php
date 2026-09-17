<div wire:poll.10s>
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-10 flex flex-col gap-6 animate-rise sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <span class="eyebrow">Overview</span>
                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-600 dark:bg-emerald-950 dark:text-emerald-300">
                        <span class="relative flex size-2">
                            <span class="absolute inline-flex size-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span>
                        </span>
                        Live
                    </span>
                </div>
                <h1 class="mt-4 font-display text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-5xl">
                    Inventory dashboard
                </h1>
                <p class="mt-3 max-w-xl text-base text-slate-500 dark:text-slate-400">
                    A live snapshot of your catalogue, stock levels and recent movements.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('products.index') }}" wire:navigate class="btn-secondary group shrink-0">
                    <span class="pr-1">Manage products</span>
                    <span class="inline-flex size-8 items-center justify-center rounded-full bg-slate-200/70 transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-0.5 dark:bg-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </span>
                </a>
                <a href="{{ route('stock.index') }}" wire:navigate class="btn-primary group shrink-0">
                    <span class="pr-1">View movements</span>
                    <span class="inline-flex size-8 items-center justify-center rounded-full bg-white/15 transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="card-shell animate-rise">
                <div class="card-core relative overflow-hidden">
                    <div class="pointer-events-none absolute -right-10 -top-10 size-32 rounded-full bg-primary-100/60 blur-3xl dark:bg-primary-500/10"></div>
                    <div class="relative flex items-center justify-between">
                        <span class="eyebrow">Products</span>
                        <div class="flex size-11 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-400 to-primary-600 shadow-md shadow-primary-600/25 dark:from-primary-500 dark:to-primary-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                        </div>
                    </div>
                    <p class="relative mt-5 font-mono text-[clamp(1.4rem,1.2rem+1.1vw,2rem)] font-bold tracking-tight tabular-nums text-slate-900 dark:text-white">{{ $stats['products'] }}</p>
                    <p class="relative mt-1.5 text-sm text-slate-500 dark:text-slate-400">across {{ $stats['categories'] }} categories</p>
                </div>
            </div>

            <div class="card-shell animate-rise" style="animation-delay: 60ms">
                <div class="card-core relative overflow-hidden">
                    <div class="pointer-events-none absolute -right-10 -top-10 size-32 rounded-full bg-emerald-100/60 blur-3xl dark:bg-emerald-500/10"></div>
                    <div class="relative flex items-center justify-between">
                        <span class="eyebrow">Units in stock</span>
                        <div class="flex size-11 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 shadow-md shadow-emerald-600/25 dark:from-emerald-500 dark:to-emerald-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 3v18h18" />
                                <path d="m19 9-5 5-4-4-3 3" />
                            </svg>
                        </div>
                    </div>
                    <p class="relative mt-5 font-mono text-[clamp(1.4rem,1.2rem+1.1vw,2rem)] font-bold tracking-tight tabular-nums text-slate-900 dark:text-white">{{ number_format($stats['totalUnits']) }}</p>
                    <p class="relative mt-1.5 text-sm text-slate-500 dark:text-slate-400">total units tracked</p>
                </div>
            </div>

            <div class="card-shell animate-rise" style="animation-delay: 120ms">
                <div class="card-core relative overflow-hidden">
                    <div class="pointer-events-none absolute -right-10 -top-10 size-32 rounded-full bg-violet-100/60 blur-3xl dark:bg-violet-500/10"></div>
                    <div class="relative flex items-center justify-between">
                        <span class="eyebrow">Stock value</span>
                        <div class="flex size-11 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-400 to-violet-600 shadow-md shadow-violet-600/25 dark:from-violet-500 dark:to-violet-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2v20" />
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                            </svg>
                        </div>
                    </div>
                    <p class="relative mt-5 font-mono text-[clamp(1.4rem,1.2rem+1.1vw,2rem)] font-bold tracking-tight tabular-nums text-slate-900 dark:text-white">₹{{ number_format(round($stats['inventoryValue'])) }}</p>
                    <p class="relative mt-1.5 text-sm text-slate-500 dark:text-slate-400">estimated on-hand value</p>
                </div>
            </div>

            <div class="card-shell animate-rise" style="animation-delay: 180ms">
                <div class="card-core relative overflow-hidden">
                    <div class="pointer-events-none absolute -right-10 -top-10 size-32 rounded-full {{ $stats['lowStock'] > 0 ? 'bg-amber-100/70 blur-3xl dark:bg-amber-500/10' : 'bg-emerald-100/60 blur-3xl dark:bg-emerald-500/10' }}"></div>
                    <div class="relative flex items-center justify-between">
                        <span class="eyebrow">Low stock</span>
                        <div class="flex size-11 items-center justify-center rounded-2xl {{ $stats['lowStock'] > 0 ? 'bg-gradient-to-br from-amber-400 to-orange-500 shadow-md shadow-orange-500/25 dark:from-amber-500 dark:to-orange-600' : 'bg-gradient-to-br from-emerald-400 to-emerald-600 shadow-md shadow-emerald-600/25 dark:from-emerald-500 dark:to-emerald-700' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 9v4" />
                                <path d="M12 17h.01" />
                                <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                            </svg>
                        </div>
                    </div>
                    <p class="relative mt-5 font-mono text-[clamp(1.4rem,1.2rem+1.1vw,2rem)] font-bold tracking-tight tabular-nums {{ $stats['lowStock'] > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-900 dark:text-white' }}">
                        {{ $stats['lowStock'] }}
                    </p>
                    <p class="relative mt-1.5 text-sm text-slate-500 dark:text-slate-400">products need attention</p>
                </div>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="card-shell animate-rise lg:col-span-2" style="animation-delay: 120ms">
                <div class="card-core">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="eyebrow">Activity</span>
                            <h2 class="mt-3 font-display text-xl font-bold text-slate-900 dark:text-white">Recent stock movements</h2>
                        </div>
                        <a href="{{ route('stock.index') }}" wire:navigate class="group inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600 transition-colors hover:text-primary-700 dark:text-primary-400">
                            View all
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14" />
                                <path d="m12 5 7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    <div class="mt-4 space-y-2">
                        @forelse ($movements as $movement)
                            <a href="{{ route('products.show', $movement->product) }}" wire:navigate class="group flex items-center gap-4 rounded-2xl border border-transparent p-3 transition-all duration-300 hover:border-slate-200/80 hover:bg-slate-50 dark:hover:border-slate-700 dark:hover:bg-slate-800/60">
                                <div class="relative shrink-0">
                                    @if ($movement->product->image_url)
                                        <img src="{{ $movement->product->image_url }}" alt="{{ $movement->product->name }}" class="size-12 rounded-xl border border-slate-200 object-cover dark:border-slate-700" />
                                    @else
                                        <span class="flex size-12 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="m12 8 6-3-6-3v10" />
                                                <path d="m20 7-8 4 8 4" />
                                                <path d="m4 11-2 2" />
                                                <path d="m4 17-2-2" />
                                                <path d="m12 12 2 3" />
                                            </svg>
                                        </span>
                                    @endif
                                    <span class="absolute -bottom-1 -right-1 flex size-7 items-center justify-center rounded-full border-2 border-white font-mono text-[10px] font-bold {{ $movement->type === \App\Enums\StockMovementType::In ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white' }} dark:border-slate-900">
                                        {{ $movement->type === \App\Enums\StockMovementType::In ? '+' : '−' }}{{ $movement->quantity }}
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-display text-sm font-semibold text-slate-900 transition-colors group-hover:text-primary-600 dark:text-white dark:group-hover:text-primary-400">
                                        {{ $movement->product->name }}
                                    </p>
                                    <p class="truncate text-xs text-slate-500 dark:text-slate-400">
                                        {{ $movement->type === \App\Enums\StockMovementType::In ? 'Stock in' : 'Stock out' }} · {{ $movement->created_at->diffForHumans() }} · {{ $movement->user?->name ?? 'System' }}
                                    </p>
                                </div>
                                <span class="shrink-0 rounded-full px-3 py-1 text-xs font-semibold {{ $movement->type === \App\Enums\StockMovementType::In ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-red-50 text-red-700 dark:bg-red-950/60 dark:text-red-300' }}">
                                    {{ $movement->type === \App\Enums\StockMovementType::In ? 'Inbound' : 'Outbound' }}
                                </span>
                            </a>
                        @empty
                            <p class="py-10 text-center text-sm text-slate-500 dark:text-slate-400">
                                No stock movements yet.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="card-shell animate-rise" style="animation-delay: 180ms">
                <div class="card-core">
                    <span class="eyebrow">Notify</span>
                    <h2 class="mt-3 font-display text-xl font-bold text-slate-900 dark:text-white">Low stock products</h2>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Restock these before they run out.</p>

                    <div class="mt-5 space-y-3">
                        @forelse (\App\Models\Product::withStockBelow(10)->with('category')->orderBy('quantity')->take(6)->get() as $product)
                            <a href="{{ route('products.show', $product) }}" wire:navigate class="group flex items-center justify-between gap-3 rounded-2xl border border-slate-100 p-4 transition-all duration-300 hover:border-primary-200 hover:bg-primary-50/50 dark:border-slate-800 dark:hover:border-primary-800 dark:hover:bg-primary-900/20">
                                <div class="flex min-w-0 items-center gap-3">
                                    @if ($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="size-11 shrink-0 rounded-xl border border-slate-200 object-cover dark:border-slate-700" />
                                    @else
                                        <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="m12 8 6-3-6-3v10" />
                                                <path d="m20 7-8 4 8 4" />
                                                <path d="m4 11-2 2" />
                                                <path d="m4 17-2-2" />
                                                <path d="m12 12 2 3" />
                                            </svg>
                                        </span>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $product->name }}</p>
                                        <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ $product->category->name }}</p>
                                    </div>
                                </div>
                                <span class="shrink-0 rounded-full {{ $product->quantity === 0 ? 'bg-red-50 text-red-700 dark:bg-red-950/60 dark:text-red-300' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300' }} px-3 py-1 font-mono text-xs font-bold">
                                    {{ $product->quantity }} {{ $product->unit }}
                                </span>
                            </a>
                        @empty
                            <p class="rounded-2xl bg-emerald-50 px-4 py-6 text-center text-sm font-medium text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300">
                                All products are well stocked.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>