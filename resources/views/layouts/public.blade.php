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
        <div class="min-h-screen bg-background text-foreground">
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
                        <a href="{{ route('catalogue.index') }}" wire:navigate class="hidden text-sm font-semibold text-slate-600 transition-colors duration-300 hover:text-slate-900 sm:block dark:text-slate-400 dark:hover:text-white">
                            Catalogue
                        </a>

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

                        @auth
                            <a href="{{ route('dashboard') }}" wire:navigate class="btn-primary !px-5 !py-2.5">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" wire:navigate class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition-all duration-300 hover:border-slate-300 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">
                                Log in
                            </a>
                        @endauth
                    </div>
                </div>
            </nav>

            <main class="pt-16">
                {{ $slot }}
            </main>

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
        </div>
    </body>
</html>