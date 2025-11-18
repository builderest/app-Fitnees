@extends('layouts.app')

@section('content')
<div class="grid gap-6 md:grid-cols-2">
    <x-card>
        <h2 class="text-xl font-semibold">Free</h2>
        <p class="mt-2 text-sm text-slate-400">Historial 6 meses, generador limitado.</p>
    </x-card>
    <x-card>
        <h2 class="text-xl font-semibold">Premium</h2>
        <p class="mt-2 text-sm text-slate-400">Acceso total a rutinas, stats y modo sesión.</p>
        <form method="POST" action="{{ route('pricing.activate') }}" class="mt-4">
            @csrf
            <x-button>Activar demo premium</x-button>
        </form>
    </x-card>
</div>
@endsection
