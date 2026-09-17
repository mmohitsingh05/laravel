@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'mb-6 flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300']) }}>
        {{ $status }}
    </div>
@endif