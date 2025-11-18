@extends('layouts.app')

@section('content')
<x-card>
    <h1 class="text-2xl font-bold">Generador inteligente</h1>
    <form method="POST" action="{{ route('generator.generate') }}" class="mt-4 space-y-4">
        @csrf
        <label class="block text-sm text-slate-300">
            Equipamiento disponible
            <select name="equipment[]" multiple class="mt-1 w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-2">
                <option value="bodyweight">Peso corporal</option>
                <option value="dumbbells">Mancuernas</option>
                <option value="barbell">Barra</option>
                <option value="machines">Máquinas</option>
                <option value="bands">Bandas</option>
            </select>
        </label>
        <label class="block text-sm text-slate-300">
            Músculos
            <select name="muscles[]" multiple class="mt-1 w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-2">
                <option value="pecho">Pecho</option>
                <option value="espalda">Espalda</option>
                <option value="piernas">Piernas</option>
                <option value="gluteos">Glúteos</option>
                <option value="hombros">Hombros</option>
                <option value="brazos">Brazos</option>
                <option value="core">Core</option>
            </select>
        </label>
        <x-input label="Número de ejercicios" name="count" type="number" min="3" max="12" value="{{ old('count', 6) }}" />
        <x-button>Generar</x-button>
    </form>
</x-card>

@if(isset($generated))
    <x-card class="mt-6">
        <h2 class="text-xl font-semibold">Resultado</h2>
        <ol class="mt-4 space-y-2">
            @foreach($generated as $item)
                <li class="rounded-xl border border-slate-800 p-3">
                    <p class="font-semibold">{{ $item['order'] }}. {{ $item['exercise']->name }}</p>
                    <p class="text-sm text-slate-400">{{ $item['sets'] }} x {{ $item['reps'] }} • Descanso {{ $item['rest_seconds'] }}s</p>
                </li>
            @endforeach
        </ol>
    </x-card>
@endif
@endsection
