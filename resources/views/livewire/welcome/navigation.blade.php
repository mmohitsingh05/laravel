<?php

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Volt\Component;

new class extends Component
{
    public function products(): Collection
    {
        return Product::with(['category'])
            ->latest('id')
            ->take(6)
            ->get();
    }

    public function stats(): array
    {
        return [
            'products' => Product::count(),
            'categories' => \App\Models\Category::count(),
            'totalUnits' => Product::sum('quantity'),
            'lowStock' => Product::withStockBelow(10)->count(),
        ];
    }
}; ?>

<div>
    <nav class="fixed inset-x-0 top-0 z-40 border-b border-slate-200/60 bg-white/70 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/70">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="/" wire:navigate class="flex items-center gap-2.5">
                <div class="flex size-9 items-center justify-center rounded-xl bg-primary-600 font-display text-sm font-bold text-white shadow-md shadow-primary-600/25">
                    IM
                </div>
                <span class="font-display text-sm font-bold tracking-tight text-slate-900 dark:text-white">
                    Inventory
                </span>
            </a>

            <div class="flex items-center gap-3">
                <button
                    type="button"
                    x-data
                    @click="const isDark = document.documentElement.classList.toggle('dark'); localStorage.setItem('theme', isDark ? 'dark' : 'light');"
                    class="inline-flex size-9 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition-colors duration-300 hover:bg-slate-50 hover:text-slate-900 dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
                    aria-label="Toggle theme"
                >
                    <svg x-show="document.documentElement.classList.contains('dark')" xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="4" />
                        <path d="M12 2v2" />
                        <path d="M12 20v2" />
                        <path d="m4.93 4.93 1.41 1.41" />
                        <path d="m17.66 17.66 1.41 1.41" />
                        <path d="M2 12h2" />
                        <path d="M20 12h2" />
                        <path d="m6.34 17.66-1.41 1.41" />
                        <path d="m19.07 4.93-1.41 1.41" />
                    </svg>
                    <svg x-show="! document.documentElement.classList.contains('dark')" x-cloak xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                    </svg>
                </button>

                <a href="{{ route('catalogue.index') }}" wire:navigate class="hidden text-sm font-semibold text-slate-600 transition-colors duration-300 hover:text-slate-900 sm:block dark:text-slate-400 dark:hover:text-white">
                    Catalogue
                </a>

                @auth
                    <a href="{{ route('dashboard') }}" wire:navigate class="btn-primary !px-5 !py-2.5">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" wire:navigate class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition-all duration-300 hover:border-slate-300 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" wire:navigate class="btn-primary !px-5 !py-2.5">
                            Register
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <main class="relative overflow-hidden">
        <div class="pointer-events-none absolute -top-40 right-0 size-[500px] rounded-full bg-primary-200/40 blur-3xl dark:bg-primary-900/20"></div>
        <div class="pointer-events-none absolute left-0 top-1/3 size-[400px] rounded-full bg-emerald-200/30 blur-3xl dark:bg-emerald-900/10"></div>

        <section class="mx-auto max-w-7xl px-4 pb-24 pt-40 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <span class="eyebrow">Inventory Management</span>
                <h1 class="mt-6 font-display text-5xl font-extrabold leading-[1.05] tracking-tight text-slate-900 dark:text-white sm:text-6xl lg:text-7xl">
                    Keep stock under<br />
                    <span class="bg-gradient-to-r from-primary-600 via-primary-500 to-emerald-500 bg-clip-text text-transparent dark:from-primary-400 dark:via-primary-300 dark:to-emerald-400">
                        total control
                    </span>
                </h1>
                <p class="mx-auto mt-6 max-w-xl text-lg leading-relaxed text-slate-500 dark:text-slate-400">
                    Organise categories, manage products and track every stock movement — in and out — with automatic, always-accurate quantity updates.
                </p>
                <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" wire:navigate class="btn-primary group">
                            <span class="pr-1">Open dashboard</span>
                            <span class="inline-flex size-8 items-center justify-center rounded-full bg-white/15 transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14" />
                                    <path d="m12 5 7 7-7 7" />
                                </svg>
                            </span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" wire:navigate class="btn-primary group">
                            <span class="pr-1">Get started</span>
                            <span class="inline-flex size-8 items-center justify-center rounded-full bg-white/15 transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14" />
                                    <path d="m12 5 7 7-7 7" />
                                </svg>
                            </span>
                        </a>
                    @endauth
                </div>
            </div>

            <div class="mx-auto mt-20 grid max-w-5xl grid-cols-1 gap-6 sm:grid-cols-3">
                <div class="card-shell">
                    <div class="card-core">
                        <div class="flex size-11 items-center justify-center rounded-2xl bg-primary-50 dark:bg-primary-900/50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-primary-600 dark:text-primary-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="20" x="2" y="2" rx="5" />
                                <rect width="8" height="8" x="8" y="8" rx="1" />
                            </svg>
                        </div>
                        <h3 class="mt-4 font-display text-lg font-bold text-slate-900 dark:text-white">Categories</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">Group products logically so your catalogue stays organised and easy to navigate.</p>
                    </div>
                </div>

                <div class="card-shell">
                    <div class="card-core">
                        <div class="flex size-11 items-center justify-center rounded-2xl bg-emerald-50 dark:bg-emerald-950/50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                                <path d="m3.3 7 8.7 5 8.7-5" />
                                <path d="M12 22V12" />
                            </svg>
                        </div>
                        <h3 class="mt-4 font-display text-lg font-bold text-slate-900 dark:text-white">Products</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">Create and maintain a full product catalogue with SKUs, units and descriptions.</p>
                    </div>
                </div>

                <div class="card-shell">
                    <div class="card-core">
                        <div class="flex size-11 items-center justify-center rounded-2xl bg-amber-50 dark:bg-amber-950/50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-amber-600 dark:text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M15 6v6a4 4 0 0 1-4 4H6" />
                                <path d="M9 10l-3 3 3 3" />
                                <path d="M21 6v2a4 4 0 0 1-4 4h-2" />
                            </svg>
                        </div>
                        <h3 class="mt-4 font-display text-lg font-bold text-slate-900 dark:text-white">Stock entries</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">Record stock in and out with quantities that update automatically — never into the negative.</p>
                    </div>
                </div>
            </div>

            <div class="mx-auto mt-10 max-w-5xl rounded-[2rem] border border-slate-200/80 bg-white/60 px-8 py-8 shadow-sm backdrop-blur dark:border-slate-800 dark:bg-slate-900/60">
                <div class="grid grid-cols-2 gap-8 lg:grid-cols-4">
                    <div class="text-center">
                        <p class="font-mono text-3xl font-bold text-slate-900 dark:text-white sm:text-4xl">{{ $this->stats()['products'] }}</p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-400">Products</p>
                    </div>
                    <div class="text-center">
                        <p class="font-mono text-3xl font-bold text-slate-900 dark:text-white sm:text-4xl">{{ $this->stats()['categories'] }}</p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-400">Categories</p>
                    </div>
                    <div class="text-center">
                        <p class="font-mono text-3xl font-bold text-slate-900 dark:text-white sm:text-4xl">{{ $this->stats()['totalUnits'] }}</p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-400">Units in stock</p>
                    </div>
                    <div class="text-center">
                        <p class="font-mono text-3xl font-bold text-amber-600 dark:text-amber-400 sm:text-4xl">{{ $this->stats()['lowStock'] }}</p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-400">Low stock</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8" id="products">
            <div class="text-center">
                <span class="eyebrow">Featured products</span>
                <h2 class="mt-4 font-display text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">
                    A sample of what's in stock
                </h2>
                <p class="mx-auto mt-3 max-w-xl text-base text-slate-500 dark:text-slate-400">
                    Every product carries a category, SKU, unit, price and a live stock level that stays accurate automatically.
                </p>
            </div>

            @if ($this->products()->isNotEmpty())
                <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($this->products() as $product)
                        <div class="card-shell">
                            <div class="card-core">
                                <div class="flex items-start justify-between gap-4">
                                    @if ($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="size-16 rounded-2xl border border-slate-200 object-cover dark:border-slate-700" />
                                    @else
                                        <div class="flex size-16 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="m12 8 6-3-6-3v10" />
                                                <path d="m20 7-8 4 8 4" />
                                                <path d="m4 11-2 2" />
                                                <path d="m4 17-2-2" />
                                                <path d="m12 12 2 3" />
                                            </svg>
                                        </div>
                                    @endif
                                    <span class="inline-flex items-center rounded-full bg-primary-50 px-3 py-1 text-xs font-medium text-primary-700 dark:bg-primary-900/50 dark:text-primary-300">
                                        {{ $product->category->name }}
                                    </span>
                                </div>
                                <h3 class="mt-4 font-display text-lg font-bold text-slate-900 dark:text-white">{{ $product->name }}</h3>
                                <p class="mt-1 font-mono text-xs text-slate-400 dark:text-slate-500">{{ $product->sku }}</p>
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

                <div class="mt-10 text-center">
                    <a href="{{ route('catalogue.index') }}" wire:navigate class="btn-primary">
                        Browse full catalogue
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </a>
                </div>
            @endif
        </section>

        <section class="border-t border-slate-200/60 bg-white py-24 dark:border-slate-800 dark:bg-slate-900/40">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-3xl text-center">
                    <span class="eyebrow">How it works</span>
                    <h2 class="mt-4 font-display text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">
                        Three steps to full control
                    </h2>
                </div>

                <div class="mx-auto mt-14 grid max-w-5xl grid-cols-1 gap-8 md:grid-cols-3">
                    <div class="flex flex-col items-start gap-4 md:items-center md:text-center">
                        <div class="flex size-12 items-center justify-center rounded-2xl bg-primary-600 font-mono text-lg font-bold text-white shadow-lg shadow-primary-600/25">1</div>
                        <div>
                            <h3 class="font-display text-lg font-bold text-slate-900 dark:text-white">Create categories</h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">Set up the groups that keep your catalogue organised and scannable.</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-start gap-4 md:items-center md:text-center">
                        <div class="flex size-12 items-center justify-center rounded-2xl bg-primary-600 font-mono text-lg font-bold text-white shadow-lg shadow-primary-600/25">2</div>
                        <div>
                            <h3 class="font-display text-lg font-bold text-slate-900 dark:text-white">Add products</h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">Give every item a SKU, unit, price and image so nothing is ambiguous.</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-start gap-4 md:items-center md:text-center">
                        <div class="flex size-12 items-center justify-center rounded-2xl bg-primary-600 font-mono text-lg font-bold text-white shadow-lg shadow-primary-600/25">3</div>
                        <div>
                            <h3 class="font-display text-lg font-bold text-slate-900 dark:text-white">Track stock</h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">Log movements in and out — quantities update automatically and never go negative.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="px-4 pb-24 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-5xl overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-primary-600 via-primary-700 to-emerald-600 px-8 py-16 text-center shadow-2xl shadow-primary-600/25 dark:from-primary-700 dark:via-primary-800 dark:to-emerald-800 sm:px-16">
                <h2 class="font-display text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                    Ready to take control of your stock?
                </h2>
                <p class="mx-auto mt-4 max-w-xl text-base text-primary-100">
                    Set up your catalogue and start recording real stock movements in minutes.
                </p>
                <div class="mt-8">
                    @auth
                        <a href="{{ route('dashboard') }}" wire:navigate class="inline-flex items-center justify-center rounded-full bg-white px-7 py-3 text-sm font-bold text-primary-700 shadow-lg transition-all duration-300 hover:bg-primary-50">
                            Open dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" wire:navigate class="inline-flex items-center justify-center rounded-full bg-white px-7 py-3 text-sm font-bold text-primary-700 shadow-lg transition-all duration-300 hover:bg-primary-50">
                            Try it free
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <footer class="border-t border-slate-200/60 bg-white dark:border-slate-800 dark:bg-slate-950">
            <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-6 px-4 py-10 sm:px-6 md:flex-row lg:px-8">
                <div class="flex items-center gap-2.5">
                    <div class="flex size-8 items-center justify-center rounded-lg bg-primary-600 font-display text-xs font-bold text-white">IM</div>
                    <span class="font-display text-sm font-bold text-slate-900 dark:text-white">Inventory</span>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    © {{ date('Y') }} Inventory. All rights reserved.
                </p>
            </div>
        </footer>
    </main>
</div>