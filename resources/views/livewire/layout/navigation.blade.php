<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <div
        x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-40 flex w-72 flex-col border-r border-slate-200/80 bg-white transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] dark:border-slate-800 dark:bg-slate-900 lg:translate-x-0"
    >
        <div class="flex h-16 items-center justify-between gap-3 border-b border-slate-200/80 px-5 dark:border-slate-800">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2.5">
                <div class="flex size-9 items-center justify-center rounded-xl bg-primary-600 font-display text-sm font-bold text-white shadow-md shadow-primary-600/25">
                    IM
                </div>
                <span class="font-display text-sm font-bold tracking-tight text-slate-900 dark:text-white">
                    Inventory
                </span>
            </a>

            <button
                type="button"
                @click="sidebarOpen = false"
                class="inline-flex size-8 items-center justify-center rounded-lg text-slate-400 transition-colors duration-300 hover:bg-slate-100 hover:text-slate-600 lg:hidden dark:hover:bg-slate-800 dark:hover:text-slate-300"
                aria-label="Close navigation"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 space-y-8 overflow-y-auto px-4 py-8">
            <div>
                <p class="px-3 text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">
                    {{ __('Overview') }}
                </p>

                <nav class="mt-3 space-y-1">
                    <a href="{{ route('dashboard') }}" wire:navigate @if (request()->routeIs('dashboard')) aria-current="page" @endif class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-700 dark:bg-primary-950/60 dark:text-primary-300' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="7" height="9" x="3" y="3" rx="1" />
                            <rect width="7" height="5" x="14" y="3" rx="1" />
                            <rect width="7" height="9" x="14" y="12" rx="1" />
                            <rect width="7" height="5" x="3" y="16" rx="1" />
                        </svg>
                        {{ __('Dashboard') }}
                    </a>

                    <a href="{{ route('categories.index') }}" wire:navigate @if (request()->routeIs('categories.*')) aria-current="page" @endif class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition-all duration-300 {{ request()->routeIs('categories.*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-950/60 dark:text-primary-300' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                            <path d="M6 12v5c3 3 9 3 12 0v-5" />
                        </svg>
                        {{ __('Categories') }}
                    </a>

                    <a href="{{ route('products.index') }}" wire:navigate @if (request()->routeIs('products.*')) aria-current="page" @endif class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition-all duration-300 {{ request()->routeIs('products.*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-950/60 dark:text-primary-300' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z" />
                            <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12" />
                        </svg>
                        {{ __('Products') }}
                    </a>
                </nav>
            </div>
        </div>

        <div class="border-t border-slate-200/80 px-4 py-4 dark:border-slate-800">
            <div class="flex items-center gap-3 rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/60">
                <div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-primary-600 text-sm font-bold text-white">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name" class="truncate text-sm font-semibold text-slate-900 dark:text-white"></div>
                    <div class="truncate text-xs text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</div>
                </div>
            </div>
        </div>
    </div>

    <div
        x-show="sidebarOpen"
        x-cloak
        x-transition:enter="transition-opacity duration-300 ease-[cubic-bezier(0.32,0.72,0,1)]"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-200 ease-in"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-30 bg-slate-950/50 backdrop-blur-sm lg:hidden"
    ></div>
</div>