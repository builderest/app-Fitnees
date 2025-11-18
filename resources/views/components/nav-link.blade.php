@props(['href', 'label'])
<a href="{{ $href }}" class="text-sm font-medium text-slate-300 hover:text-white {{ request()->url() === $href ? 'text-white' : '' }}">
    {{ $label }}
</a>
