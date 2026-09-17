<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <span class="eyebrow">Get started</span>
    <h1 class="mt-4 font-display text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">
        Create your account
    </h1>
    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
        Start tracking your inventory in minutes.
    </p>

    <form wire:submit="register" class="mt-8 space-y-5">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" class="mt-2" type="text" name="name" required autofocus autocomplete="name" placeholder="Jane Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="mt-2" type="email" name="email" required autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="password" id="password" class="mt-2"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="mt-2"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <x-primary-button class="!mt-8">
            {{ __('Create account') }}
        </x-primary-button>
    </form>

    <p class="mt-8 text-center text-sm text-slate-500 dark:text-slate-400">
        Already have an account?
        <a href="{{ route('login') }}" wire:navigate class="font-semibold text-primary-600 transition-colors hover:text-primary-700 dark:text-primary-400">
            {{ __('Log in') }}
        </a>
    </p>
</div>
