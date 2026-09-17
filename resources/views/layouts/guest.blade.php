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
        <div class="flex min-h-screen flex-col items-center justify-center bg-slate-50 px-4 py-12 dark:bg-slate-950 sm:px-6">
            <div class="mb-8">
                <a href="/" wire:navigate class="flex items-center gap-2.5">
                    <div class="flex size-11 items-center justify-center rounded-2xl bg-primary-600 font-display text-lg font-bold text-white shadow-lg shadow-primary-600/30">
                        IM
                    </div>
                </a>
            </div>

            <div class="w-full max-w-md rounded-[2rem] border border-slate-200/80 bg-white p-8 shadow-lg shadow-slate-200/50 dark:border-slate-800 dark:bg-slate-900 dark:shadow-none sm:p-10">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>