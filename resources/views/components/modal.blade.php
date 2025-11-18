@props(['title', 'trigger' => 'Abrir'])
<div x-data="{ open: false }">
    <button @click="open = true" class="rounded-xl border border-slate-700 px-3 py-1 text-sm">{{ $trigger }}</button>
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/80">
        <div class="w-full max-w-lg rounded-2xl bg-slate-900 p-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">{{ $title }}</h3>
                <button @click="open = false">✕</button>
            </div>
            <div class="mt-4">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
