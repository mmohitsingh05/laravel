<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Inventory') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=jetbrains-mono:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Theme -->
        <script>
            (function () {
                const stored = localStorage.getItem('theme');
                const theme = stored ?? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.classList.toggle('dark', theme === 'dark');
            })();
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        @php
            $pageTitle = match (request()->route()?->getName()) {
                'dashboard' => 'Dashboard',
                'categories.index' => 'Category catalogue',
                'products.index' => 'Products',
                'products.show' => 'Product details',
                'products.edit' => 'Edit product',
                'stock.create' => 'Stock entry',
                'profile' => 'Profile',
                default => config('app.name', 'Inventory'),
            };
        @endphp

        <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-slate-50 dark:bg-slate-950">
            <livewire:layout.navigation />

            <div class="flex min-h-screen flex-col lg:pl-72">
                <header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/80 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/80">
                    <div class="flex h-16 items-center gap-3 px-4 sm:px-6 lg:px-8">
                        <button
                            type="button"
                            @click="sidebarOpen = ! sidebarOpen"
                            class="inline-flex size-10 items-center justify-center rounded-xl text-slate-500 transition-colors duration-300 hover:bg-slate-100 hover:text-slate-900 lg:hidden dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
                            aria-label="Toggle navigation"
                        >
                            <svg x-show="! sidebarOpen" xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 6h16" />
                                <path d="M4 12h16" />
                                <path d="M4 18h16" />
                            </svg>
                            <svg x-show="sidebarOpen" x-cloak xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>

                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $pageTitle }}</p>
                            <p class="hidden text-xs text-slate-500 dark:text-slate-400 sm:block">
                                {{ __('Inventory management console') }}
                            </p>
                        </div>

                        <button
                            type="button"
                            x-data
                            x-bind:aria-label="document.documentElement.classList.contains('dark') ? 'Switch to light mode' : 'Switch to dark mode'"
                            @click="const isDark = document.documentElement.classList.toggle('dark'); localStorage.setItem('theme', isDark ? 'dark' : 'light');"
                            class="inline-flex size-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition-colors duration-300 hover:bg-slate-50 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-white"
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

                        <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                            <button
                                type="button"
                                @click="open = ! open"
                                class="inline-flex items-center gap-2.5 rounded-full border border-slate-200 bg-white py-1.5 pl-1.5 pr-3 text-sm font-semibold text-slate-700 shadow-sm transition-colors duration-300 hover:border-slate-300 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-slate-600 dark:hover:bg-slate-700"
                            >
                                <div class="flex size-7 items-center justify-center rounded-full bg-primary-600 text-xs font-bold text-white">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name" class="hidden max-w-[120px] truncate sm:block"></div>
                                <svg x-bind:class="open ? 'rotate-180' : ''" class="size-4 text-slate-400 transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)]" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div
                                x-show="open"
                                x-cloak
                                x-transition:enter="transition ease-out duration-200 origin-top-right"
                                x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
                                class="absolute right-0 mt-2 w-56 rounded-2xl border border-slate-200 bg-white py-2 shadow-xl dark:border-slate-700 dark:bg-slate-800"
                            >
                                <div class="border-b border-slate-100 px-4 py-2.5 dark:border-slate-700">
                                    <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                                    <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('profile') }}" wire:navigate class="mx-1.5 mt-1.5 flex items-center gap-2.5 rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition-colors duration-300 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-700 dark:hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                        <circle cx="12" cy="7" r="4" />
                                    </svg>
                                    {{ __('Profile') }}
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="mx-1.5 mb-1.5">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition-colors duration-300 hover:bg-red-50 hover:text-destructive dark:text-slate-300 dark:hover:bg-red-950/40">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-slate-400 transition-colors group-hover:text-destructive" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                            <polyline points="16 17 21 12 16 7" />
                                            <line x1="21" x2="9" y1="12" y2="12" />
                                        </svg>
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>