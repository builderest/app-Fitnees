@extends('layouts.app')

@section('content')
<x-card>
    <h1 class="text-2xl font-bold">Registrar sesión</h1>
    <form method="POST" action="{{ route('sessions.store') }}" class="mt-4 grid gap-4 md:grid-cols-2">
        @csrf
        <x-input label="ID Programa" name="workout_program_id" />
        <x-input label="ID Día" name="workout_day_id" />
        <x-input label="Intensidad" name="intensity" />
        <x-input label="Notas" name="notes" class="md:col-span-2" />
        <x-button class="md:col-span-2">Guardar sesión</x-button>
    </form>
</x-card>

<div class="mt-6 space-y-4">
    @foreach($sessions as $session)
        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400">{{ optional($session->performed_at)->format('d M Y') }}</p>
                    <p class="font-semibold">{{ $session->intensity ?? 'Sin intensidad' }}</p>
                </div>
                <p class="text-sm text-slate-400">{{ $session->notes }}</p>
            </div>
        </x-card>
    @endforeach
</div>
<div class="mt-6">{{ $sessions->links() }}</div>
@endsection
