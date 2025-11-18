@props(['label', 'name', 'type' => 'text', 'value' => null])
<label class="block text-sm font-medium text-slate-300">
    {{ $label }}
    <input type="{{ $type }}" name="{{ $name }}" value="{{ $value ?? old($name) }}" {{ $attributes->merge(['class' => 'mt-1 w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-2 text-sm text-white focus:border-rose-500 focus:ring-rose-500']) }}>
</label>
@error($name)
    <p class="mt-1 text-sm text-rose-400">{{ $message }}</p>
@enderror
