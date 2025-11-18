@extends('layouts.app')

@section('content')
<x-card>
    <h1 class="text-2xl font-bold">Progreso corporal</h1>
    <form method="POST" action="{{ route('progress.store') }}" class="mt-4 grid gap-4 md:grid-cols-3">
        @csrf
        <x-input label="Fecha" name="date" type="date" />
        <x-input label="Peso" name="weight" />
        <x-input label="Body fat" name="body_fat" />
        <x-input label="Pecho" name="chest" />
        <x-input label="Cintura" name="waist" />
        <x-input label="Brazos" name="arms" />
        <x-input label="Piernas" name="legs" />
        <x-input label="Notas" name="notes" class="md:col-span-3" />
        <x-button class="md:col-span-3">Guardar</x-button>
    </form>
</x-card>

<x-card class="mt-6">
    <h2 class="text-lg font-semibold">Registros recientes</h2>
    <div class="mt-4 grid gap-4 md:grid-cols-3">
        @foreach($entries as $entry)
            <div class="rounded-xl border border-slate-800 p-4">
                <p class="text-sm text-slate-400">{{ $entry->date->format('d/m') }}</p>
                <p class="text-xl font-bold">{{ $entry->weight }}kg</p>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $entries->links() }}</div>
</x-card>

<x-card class="mt-6">
    <h2 class="text-lg font-semibold">Peso corporal</h2>
    <canvas id="weightChart" class="mt-4"></canvas>
</x-card>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('weightChart');
    const series = @json($series);
    if (series.length) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: series.map(point => point.date),
                datasets: [{
                    label: 'Peso (kg)',
                    data: series.map(point => point.weight),
                    borderColor: '#f43f5e',
                    backgroundColor: 'rgba(244,63,94,0.2)',
                    tension: 0.3,
                    fill: true,
                }]
            },
            options: {
                plugins: { legend: { labels: { color: 'white' } } },
                scales: {
                    x: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(148,163,184,0.1)' } },
                    y: { ticks: { color: '#94a3b8' }, grid: { color: 'rgba(148,163,184,0.1)' } }
                }
            }
        });
    }
</script>
@endpush
@endsection
