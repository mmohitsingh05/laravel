@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-2xl border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white']) }}>