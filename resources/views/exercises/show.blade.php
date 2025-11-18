@extends('layouts.app')

@section('content')
<x-card>
    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <h1 class="text-2xl font-bold">{{ $exercise->name }}</h1>
            <p class="mt-2 text-sm text-slate-300">{{ $exercise->description }}</p>
            <p class="mt-4 text-sm text-slate-400">Músculos secundarios: {{ implode(', ', $exercise->secondary_muscles ?? []) }}</p>
            <button class="mt-4 rounded-xl border border-slate-600 px-4 py-2 text-sm">Agregar a rutina</button>
        </div>
        <div>
            <iframe class="aspect-video w-full rounded-2xl" src="{{ $exercise->video_url }}" allowfullscreen></iframe>
        </div>
    </div>
</x-card>
@endsection
