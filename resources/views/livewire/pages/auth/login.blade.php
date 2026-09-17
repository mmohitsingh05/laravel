<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <span class="eyebrow">Welcome back</span>
    <h1 class="mt-4 font-display text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
        Sign in to your account
    </h1>
    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
        Manage your categories, products and stock from one place.
    </p>

    <!-- Session Status -->
    <x-auth-session-status class="mt-6" :status="session('status')" />

    <form wire:submit="login" class="mt-8 space-y-5">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="form.email" id="email" class="mt-2" type="email" name="email" required autofocus autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="form.password" id="password" class="mt-2"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-slate-300 text-primary-600 shadow-sm focus:ring-primary-500 dark:border-slate-600 dark:bg-slate-800 dark:focus:ring-primary-500" name="remember">
                <span class="ms-2 text-sm text-slate-600 dark:text-slate-400">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="rounded-md text-sm font-semibold text-primary-600 transition-colors hover:text-primary-700 dark:text-primary-400" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <x-primary-button class="!mt-8">
            {{ __('Log in') }}
        </x-primary-button>
    </form>

    <p class="mt-8 text-center text-sm text-slate-500 dark:text-slate-400">
        Don't have an account?
        <a href="{{ route('register') }}" wire:navigate class="font-semibold text-primary-600 transition-colors hover:text-primary-700 dark:text-primary-400">
            {{ __('Register') }}
        </a>
    </p>
</div>
