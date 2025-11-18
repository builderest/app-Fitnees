@extends('layouts.app')

@section('content')
<x-card>
    <form method="GET" class="grid gap-4 md:grid-cols-4">
        <x-input label="Grupo muscular" name="muscle" value="{{ request('muscle') }}" />
        <x-input label="Equipamiento" name="equipment" value="{{ request('equipment') }}" />
        <x-input label="Dificultad" name="difficulty" value="{{ request('difficulty') }}" />
        <x-input label="Buscar" name="search" value="{{ request('search') }}" />
        <x-button class="md:col-span-4">Filtrar</x-button>
    </form>
</x-card>

<div class="mt-6 grid gap-4 md:grid-cols-3">
    @foreach($exercises as $exercise)
        <x-card>
            <h3 class="text-lg font-semibold">{{ $exercise->name }}</h3>
            <p class="mt-1 text-sm text-slate-400">{{ $exercise->primary_muscle }} • {{ ucfirst($exercise->difficulty) }}</p>
            <a href="{{ route('exercises.show', $exercise) }}" class="mt-3 inline-flex text-sm text-rose-400">Ver detalle</a>
        </x-card>
    @endforeach
</div>

<div class="mt-6">
    {{ $exercises->links() }}
</div>
@endsection
