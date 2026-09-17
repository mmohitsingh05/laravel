<div>
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-10 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <span class="eyebrow">Categories</span>
                <h1 class="mt-4 font-display text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-5xl">
                    Category catalogue
                </h1>
                <p class="mt-3 max-w-xl text-base text-slate-500 dark:text-slate-400">
                    Organise your products into groups to keep your inventory structured and searchable.
                </p>
            </div>
            <button
                type="button"
                wire:click="$dispatch('open-category-form')"
                class="btn-primary group shrink-0"
            >
                <span class="pr-1">New category</span>
                <span class="inline-flex size-8 items-center justify-center rounded-full bg-white/15 transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] group-hover:translate-x-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                </span>
            </button>
        </div>

        @if (session('message'))
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21.801 10A10 10 0 1 1 17 3.335" />
                    <path d="m9 11 3 3L22 4" />
                </svg>
                {{ session('message') }}
            </div>
        @endif

        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-display text-lg font-bold text-slate-900 dark:text-white">All categories</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $categories->total() }} total</p>
            </div>
            <div class="relative w-full sm:w-72">
                <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
                <input
                    type="search"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search categories..."
                    class="w-full rounded-full border-slate-200 bg-white py-2.5 pl-11 pr-4 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-primary-500 focus:ring-primary-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                />
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-[1.75rem] border border-slate-200/80 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Name</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Description</th>
                        <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Products</th>
                        <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-700 dark:bg-slate-900">
                    @forelse ($categories as $category)
                        <tr class="group transition-colors duration-300 hover:bg-slate-50 dark:hover:bg-slate-800/60">
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="font-display text-sm font-semibold text-slate-900 dark:text-white">{{ $category->name }}</span>
                            </td>
                            <td class="max-w-xs px-6 py-4">
                                <span class="block truncate text-sm text-slate-500 dark:text-slate-400">{{ $category->description ?? '—' }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 font-mono text-xs font-medium text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                    {{ $category->products_count }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        type="button"
                                        wire:click="$dispatch('edit-category', { id: {{ $category->id }} })"
                                        class="inline-flex items-center rounded-full px-4 py-2 text-xs font-semibold text-slate-600 transition-colors duration-300 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="confirmDelete({{ $category->id }})"
                                        class="inline-flex items-center rounded-full px-4 py-2 text-xs font-semibold text-destructive transition-colors duration-300 hover:bg-red-50 dark:hover:bg-red-950/50"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                                    {{ $search !== '' ? 'No categories match your search.' : 'No categories yet. Create your first one.' }}
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="border-t border-slate-200 px-6 py-4 dark:border-slate-700">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

    <livewire:category.category-form />

    @if ($deletingId)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-950/50 backdrop-blur-sm" wire:click="cancelDelete"></div>
            <div class="relative w-full max-w-md rounded-3xl border border-slate-200 bg-white p-8 shadow-2xl dark:border-slate-700 dark:bg-slate-900">
                <div class="flex size-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-950">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-destructive" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                        <path d="M12 9v4" />
                        <path d="M12 17h.01" />
                    </svg>
                </div>
                <h3 class="mt-5 font-display text-xl font-bold text-slate-900 dark:text-white">Delete category?</h3>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    Deleting this category will also remove all of its products and stock history. This cannot be undone.
                </p>
                <div class="mt-7 flex items-center justify-end gap-3">
                    <button type="button" wire:click="cancelDelete" class="btn-secondary">
                        Cancel
                    </button>
                    <button type="button" wire:click="delete" class="btn-danger">
                        Delete category
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>